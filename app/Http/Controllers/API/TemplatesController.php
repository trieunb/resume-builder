<?php

namespace App\Http\Controllers\API;

use App\Events\RenderImageAfterCreateTemplate;
use App\Events\sendMailAttachFile;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Template;
use App\Repositories\TemplateMarket\TemplateMarketInterface;
use App\Repositories\Template\TemplateInterface;
use App\Repositories\User\UserInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TemplatesController extends Controller
{
    protected $user;

    protected $template;

    public function __construct(UserInterface $user, TemplateInterface $template)
    {
        $this->middleware('jwt.auth');

        $this->user = $user;
        $this->template = $template;
    }

    public function getAllTemplate(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        
        return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => $this->user->getTemplateFromUser($user->id)->templates
        ]); 
    }

    public function postTemplates(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        if ($request->has('templates')) {
            $data = [];
            foreach ($request->get('templates') as $value) {
                $data[] = [
                    'user_id' => $user->id,
                    'cat_id' => $value['cat_id'],
                    'title' => $value['title'],
                    'slug' => str_slug($value['title']),
                    'content' => $value['content'],
                    'image' => $value['image'],
                    'price' => $value['price'],
                    'status' => $value['status'],
                    'type' => $value['type'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            Template::insert($data);
        }
        return response()->json(['status_code' => 200, 'status' => true]);
        
    }

    public function getDetailTemplate(Request $request, $id)
    {
        $user = \JWTAuth::toUser($request->get('token'));
      
        return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => $this->template->getDetailTemplate($id, $user->id)
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function edit(Request $request, $id)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->getDetailTemplate($id, $user->id);

        return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => ['id' => $id,
                'title' => $template->title,
                'content' => $template->content
            ]
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function editView($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->getDetailTemplate($id, $user->id);
        $content = $template->content;

        return view()->make('frontend.template.full', compact('content'));
    }

    public function postEdit($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $result = $this->template->editTemplate($id, $user->id, $request->get('content'));
        
        if (!$result) 
            return response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when edit Template']);
        
        $render = event(new RenderImageAfterCreateTemplate($result->id, $result->content, $result->slug));
        
        return $render
            ? response()->json(['status_code' => 200, 'status' => true, 'message' => 'Edit template successfully'])
            : response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when render file']);
    }

    public function postBasicTemplate(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $user_info = $this->user->getProfile($user->id);
        $dob = date("Y-m-d", $user_info->dob);
        $age = $this->user->GetAge($dob);

        $content = view('frontend.template.basic_template', ['template' => $user_info, 'age' => $age])->render();
        $template = $this->template->createTemplateBasic($user_info->id, $content);
        
        if ( !$template) {
            return response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when create template']);
        }
        
        $render = event(new RenderImageAfterCreateTemplate($template->id, $template->content, $template->slug));
        if ( !$render) {
            return response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when render pdf']);
        }
        return response()->json([
                "status_code" => 200,
                "status" => true,
                "data" => $template
            ]);
    }

    public function updateBasicTemplate(Request $request)
    {
        $template_basic = $request->get('template_basic')['content'];
        $template_bs = Template::where('type', '=', 1)->first();
        $template_bs->content = $template_basic;
        $template_bs->save();

        return response()->json([
                "status_code" => 200,
                "status" => true,
                "message" => "updated successfully"
            ]);
    }

    public function postDeleteTemplate(Request $request, $temp_id)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = Template::where('id', $temp_id)->first();
        
        if (!$template) {
            return response()->json([
                'status_code' => 404,
                'status' => false,
                'message' => 'page not found'
            ]);
        }
        
        return $this->template->deleteTemplate($user->id, $temp_id)
            ? response()->json(['status_code' => 200, 'status' => true, 'message' => 'Delete template successfully'])
            : response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when delete Template']);
    }

    public function create()
    {
        return view()->make('api.template.create');
    }

    public function postCreate(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $result = $this->template->createTemplate($user->id, $request);

        // $template = event(new RenderImageAfterCreateTemplate($result->id, $result->content, $result->title));

        return $result
            ? response()->json(['status_code' => 200, 'status' => true, 'data' => $result])
            : response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error occurred when create template']);
    }

    public function attach($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->getById($id);
        $index = strpos($template->source_file_pdf, 'pdf/');
        $sourcePDF = public_path(substr($template->source_file_pdf, $index));

        if ( ! \File::exists($sourcePDF)) {
            \PDF::loadView('api.template.index', ['content' => $template->content])
            ->save(public_path('pdf/'.$template->slug.'.pdf'));
        }
      
        event(new sendMailAttachFile($user, '', $sourcePDF));

        return response()->json(['status_code' => 200, 'status' => true, 'message' => 'success']);
    }

    public function view($id, Request $request)
    {
        $template = $this->template->getById($id);
        $content = str_replace('contenteditable="true"', '', $template->content);

        return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => ['id' => $id,'title' => $template->title,'content' => $content]
        ]);
    }

    public function test($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $content = $this->template->getDetailTemplate($id, $user->id)->content;

        return view('api.template.create', compact('content'));
    }
}