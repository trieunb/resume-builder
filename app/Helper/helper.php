<?php

if ( !function_exists('show_selected_option')) {
    function show_selected_option($categories, $selected_id = 0, $class = 'form-control', $dataAtrribute = null) {
        $html = '';
        if (count($categories)) return $html;
        $html = $class != '' ? '<select class="'.$class.'">' : '<select>';
        foreach ($categories as $category) {
            $selected = $category->id == $selected_id ? 'selected' : '';
            $html .= '<option value="'.$category->id.'" '.$selected.'>'.$category->name.'</option>';
        }

        $html .= '</select>';

        return $html;
    }
}

if (!function_exists('replace_url_img')) {
    /**
     * Replace url img for render PDF
     * @param  string $string content html
     * @return string         
     */
    function replace_url_img($string) {
        preg_match_all('@src="([^"]+)"+@', $string, $match );
        $srcs = array_pop($match);
        
        foreach ($srcs as $src) {
            $tmp = explode('uploads', $src);
            $replace = 'uploads'.array_pop($tmp);
            $string = str_replace($src, $replace, $string);
        }

        return $string;
    }
}

if (!function_exists('convertPDFToIMG')) {
    /**
     * Convert file PDF to IMG
     * @param  string  $filename 
     * @param  integer $width    
     * @param  integer $height   
     * @return void            
     */
    function convertPDFToIMG($filename, $width = 200, $height = 200) {
        $imageFile = str_random(20).uniqid();
        $img = new \Imagick();
        $img->readImage(public_path('pdf/'.$filename.'.pdf[0]'));
        $img->setImageFormat('jpg');
        $img->setSize($width, $height);
        $img->writeImage(public_path('images/template/'.$imageFile.'.jpg'));
        $img->clear();
        $img->destroy();

        return $imageFile;
    }
}

if (!function_exists('createSection')) {
    /**
     * Create section for market place
     * @param  string $htmlString String HTML
     * @param  array &$sections  
     * @param  array $result  
     * @return array 
     */
    function createSection($htmlString, &$sections, &$result = []) {
        $tmp = [];
        $html = new \Htmldom();
        $html->load($htmlString);
        $contentProfile = '';
        $str = $htmlString;
        $content = '';
        
        if (count($sections) > 0) {
            foreach ($sections as $index => $section) {
                $class = explode('.', $section);
                $class = end($class);
                
                foreach ($html->find($section) as $key => $e) {
                    if ($key != 0) {
                        $contentProfile .= '<br>'.$e->innertext;

                        $content = str_replace($e->outertext, '', $str);
                        $str = $content;
                    }
                }

                foreach ($html->find($section) as $k => $e) {
                    if ($k == 0) {
                        $contentProfile = $e->innertext.$contentProfile;
                        $outerCurrent = $e->outertext;
                        // $e->{'contentediable'} = 'true';
                        // $outer = str_replace($outerCurrent, $e->outertext, $str);

                        $content = str_replace($e->outertext,"<div contenteditable='true' class='{$class}'>".$contentProfile ."</div>", $str);

                        $tmp[$class] = "<div contenteditable='true' class='{$class}'>".$contentProfile ."</div>";
                        $tmp['content'] = $content;

                    }
                }

                unset($sections[$index]);

                if (count($sections) > 0) {

                    if (count($tmp) > 0) {
                        $result = array_merge($result, $tmp);
                        
                        $tmp = createSection($content, $sections, $result);
                        
                    }
                }
            }
        }

        return $result;
    }
}

if (!function_exists('editSection')) {
    /**
     * Edit section for template resume
     * @param  string $section Name section
     * @param  string $content String HTML for edit
     * @param  string $str     String HTML current
     * @return array          
     */
    function editSection($section, $content, $str) {
        $html = new \Htmldom();
        $html->load($str);
        $str =  preg_replace('/\t|\n+/', '', $str);
        $html_request = new \Htmldom;
        $html_request->load($content);

        $currentSectionString = '';

        // foreach ($html->find('div.'.$section) as $element) {
        //    $currentSectionString = $element->outertext;
        // }

        $replace = '<div class="'.$section.'">';

        foreach ($html_request->find('div.'.$section) as $key => $element) {
            $replace .= $key == count($html_request->find('div.'.$section)) - 1 
                ? $element->innertext
                : $element->innertext.'<br>';
        }

        $replace .= '</div>';

        foreach ($html->find('div.'.$section) as $element) {
           $element->outertext = $replace;
       }

        return [
           'content' => $html->save(),
           'section' => $replace
        ];
   }
}


if (!function_exists('createSectionData')) {
    /**
     * Create section data for show menu
     * @param  mixed $template Template Collection
     * @return array           
     */
    function createSectionData($template) {
        $section = ['template_id' => $template->id];

        if ( ! is_array($template->section)) 
            throw new \SectionTemplateException('Not found section of template');

        foreach ($template->section as $k => $v) {

            switch ($k) {

                case 'name':
                    $section['contact']['display'] = 'Contact Information';
                    $section['contact']['name'] = 'Name';
                    break;
                case 'address':
                    $section['contact']['display'] = 'Contact Information';
                    $section['contact']['address'] = 'Address';
                    break;
               /* case 'photo':
                    $section['contact']['display'] = 'Contact Information';
                    $section['contact']['photo'] = 'Photos';
                    break;*/
                case 'email':
                    $section['contact']['display'] = 'Contact Information';
                    $section['contact']['email'] = 'Email Address';
                    break;
                case 'profile_website':
                    $section['contact']['display'] = 'Contact Information';
                    $section['contact']['profile_website'] = 'My Profile Website';
                    break;
                case 'linkedin':
                    $section['contact']['display'] = 'Contact Information';
                    $section['contact']['linkedin'] = 'My LinkedIn Profile';
                    break;
                case 'phone':
                    $section['contact']['display'] = 'Contact Information';
                    $section['contact']['phone'] = 'Phone Number';
                    break;
                case 'availability':
                    $section['contact']['display'] = 'Contact Information';
                    $section['contact']['availability'] = 'Availability';
                    break;
                case 'infomation':
                    $section['contact']['display'] = 'Contact Information';
                    $section['contact']['infomation'] = 'Personal Infomation';
                    break;
                default:
                    $section[$k] = ucfirst($k);
                    break;
            }
        }

        if (isset($section['contact'])) {
            ksort($section);
        }
        return $section;
    }
}


if (!function_exists('createSectionMenu')) {
    /**
     * Create html menu
     * @param  array  $data  
     * @param  string $token Token of User
     * @return string        HTML menu
     */
    function createSectionMenu(array $data, $token) {
        $html = '<ul class="list list-unstyled">';
        $i = 0;
        foreach ($data as $section => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $i++;
                    if (strpos($v, '_') !== FALSE) {
                        $tmp = explode('_', $v);

                        $v = ucfirst($tmp[0]). ' ' .ucfirst($tmp[1]);
                    }
                    if ($k == 'display') {
                        $html .= '<li><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown">';
                        $html .= $v .'<span class="arrow right pull-right"><i class="fa fa-chevron-right"></i></span></a>';
                        $html .= '<div class="dropdown-menu" aria-labelledby="dLabel"><ul class="list list-unstyled">';
                    }else {
                     $html .= "<li><a href='/api/template/edit/".$data['template_id']."/".$k."?token={$token}'>{$v}</a></li>";

                     if (count($value) == $i && strpos($html ,'<div class="dropdown-menu" aria-labelledby="dLabel">')) {
                        $html .= '</ul></div>';
                    }
                }
            }

            $html .= '</li>';

            } else {
                if ($section != 'template_id') {
                    if (strpos($value, '_') !== FALSE) {
                        $tmp = explode('_', $value);

                        $value = ucfirst($tmp[0]). ' ' .ucfirst($tmp[1]);
                    }
                    $html .= "<li><a href='/api/template/edit/".$data['template_id']."/".$section."?token={$token}'>{$value}</a></li>";   
                }
            }
        }

    return $html .= '</ul>';
    }
}

if (!function_exists('createSectionBasic')) {
    /**
     * Create section for template basic
     * @param  string $section 
     * @param  string $content 
     * @return string          
     */
    function createSectionBasic($section, $content)
    {
        $html = new \Htmldom($content);
        foreach ($html->find($section) as $value) {
            return (string) $value;
        }
    }
}   

if (!function_exists('apply_data_for_section_infomation')) {
    /**
     * Apply data for sections personal infomation
     * @param  string $section 
     * @param  string $replace 
     * @param  string $str     
     * @return []          
     */
    function apply_data_for_section_infomation($section, $replace, $str) {
        $html = new \Htmldom($str);
        $result = [];

        if ( !$html->find('div.'.$section)) 
            return ['section' => '', 'content' => $str];

        foreach ($html->find('div.'.$section) as $value) {
            // $search = trim($value->outertext);
            // $current = $value->innertext;
            // $value->innertext = str_replace($current, $replace, strip_tags($value->innertext));

            $value->innertext = $section != 'photo'
                ? '<span>'.$replace.'</span>'
                : '<img src="'.asset($replace).'" width="100%">';
            $tmp = $value->innertext;

        }   

        // $str = str_replace($current, $replace, $str);

        return [
            'section' => '<div class="'.$section.'" contenteditable="true">'.$tmp.'</div>',
            'content' => preg_replace('/\n/', '', $html->save())
        ];;
    }
}

if (!function_exists('apply_data_for_other')) {
    /**
     * Get data apply section
     * @param  [type] $section [description]
     * @param  [type] $str     [description]
     * @return [type]          [description]
     */
    function apply_data_for_other($section, $str) {
        $html = new \Htmldom($str);
        $result = [];
        $tmp = '';

        switch ($section) {
            case 'reference':
                $tmp .= '<h3 style="font-weight:600">References</h3>';
                foreach (\App\Models\Reference::whereUserId(\Auth::user()->id)->get() as $v) {
                    $tmp .= '<ul style="list-style:none">';
                    $tmp .= '<li style="font-weight:600">'.$v->reference.'</li>';
                    $tmp .= '<li>'.$v->content.'</li>';       
                    $tmp .= '</ul>'; 

                }

                foreach ($html->find('div.'.$section) as $element) {
                    $element->innertext = $tmp;
                }

                break;
            case 'objective':
                $tmp .= '<h3 style="font-weight:600">Objectives</h3>';
                foreach (\App\Models\Objective::whereUserId(\Auth::user()->id)->get() as $v) {
                    $tmp .= '<ul style="list-style:none">';
                    $tmp .= '<li style="font-weight:600">'.$v->title.'</li>';
                    $tmp .= '<li>'.$v->content.'</li>'; 
                    $tmp .= '</ul>';                   
                }
                
                foreach ($html->find('div.'.$section) as $element) {
                    $element->innertext = $tmp;
                }

                break;
            case 'work':
                $tmp .= '<h3 style="font-weight:600">Works</h3>';
                foreach (\App\Models\UserWorkHistory::whereUserId(\Auth::user()->id)->get() as $v) {
                    $tmp .= '<label style="font-weight:600;">'.$v->job_title.'</label>';
                    $tmp .= '<ul style="list-style:none">';
                    $tmp .= '<li style="font-weight:600"><label style="font-weight:600">Company</label>: '.$v->title.'</li>';
                    $tmp .= '<li>'.$v->start.'-'.$v->end.'</li>';   
                    $tmp .= '<li>'.$v->description.'</li>';   
                    $tmp .= '</ul>';                 
                }
                
                foreach ($html->find('div.'.$section) as $element) {
                    $element->innertext = $tmp;
                }
                break;
            case 'education':
                $tmp .= '<h3 style="font-weight:600">Education</h3>'; 
                foreach (\App\Models\UserEducation::whereUserId(\Auth::user()->id)->get() as $v) {
                    $tmp .= '<label style="font-weight:600;">'.$v->title.'</label>';
                    $tmp .= '<ul style="list-style:none">';
                    $tmp .= '<li style="font-weight:600"><label style="font-weight:600">School</label>: '.$v->school_name.'</li>';
                    $tmp .= '<li>'.$v->start.'-'.$v->end.'</li>';   
                    $tmp .= '<li>'.$v->degree.'</li>';   
                    $tmp .= '<li>'.$v->result.'</li>';   
                    $tmp .= '</ul>';                 
                }

                foreach ($html->find('div.'.$section) as $element) {
                    $element->innertext = $tmp;
                }
                break;
            case 'key_quanlification':
                $tmp .= '<h3 style="font-weight:600">Qualifications</h3>'; 
                $tmp .= '<ul style="list-style:none">';

                foreach (\App\Models\Qualification::whereUserId(\Auth::user()->id)->get() as $v) {
                    $tmp .= '<li>'.$v->content.'</li>';           
                }
                $tmp .= '</ul>';  
                foreach ($html->find('div.'.$section) as $element) {
                    $element->innertext = $tmp;
                }

                break;
            case 'personal_test':
                $tmp .= '<h3 style="font-weight:600">Skills</h3>'; 
                foreach (\App\Models\UserSkill::whereUserId(\Auth::user()->id)->get() as $v) {
                    $tmp .= '<ul style="list-style:none">';
                    $tmp .= '<li><label style="font-weight:600">Name: </label>'.$v->skill_name.'</li>';
                    $tmp .= '<li><label style="font-weight:600">Point: </label>'.$v->skill_test_point.'</li>';
                    $tmp .= '</ul>';  
                }
                
                foreach ($html->find('div.'.$section) as $element) {
                    $element->innertext = $tmp;
                }

                break;
            default:
                return null;
                break;
        }

        return [
            'section' => '<div class="'.$section.'" contenteditable="true">'.$tmp.'</div>',
            'content' => $html->save()
        ];
    }
}

if (!function_exists('createClassSection')) {
    /**
     * create class for section
     * @return [] 
     */
    function createClassSection()
    {
        return ['div.name', 'div.address', 'div.phone',
            'div.email', 'div.profile_website', 'div.linkedin',
            'div.reference', 'div.objective', 'div.activitie',
            'div.work', 'div.education', 'div.photo', 'div.personal_test',
            'div.key_quanlification', 'div.availability', 'div.infomation'
        ];
    }
}

