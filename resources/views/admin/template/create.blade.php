@extends('admin.layout')


@section('page-header')
Create Template
@stop

@section('content')
<div class="row">
    @if (\Session::has('message'))
    <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>{{ \Session::get('message') }}</strong></div>
    @endif
    <div class="col-lg-12">
        <div id="message"></div>
        <form action="{{ route('admin.template.post.create') }}" id="create-form" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="Title">
            </div>
            <div class="form-group">
                <label for="cat_id">Category</label>
                <select name="cat_id" id="cat_id" class="form-control" >
                    <option value="">Select</option>
                    <option value="1">Category</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" name="price" class="form-control" id="price" placeholder="Price">
            </div>

            <div class="form-group">
                <textarea id="content" name="content"></textarea> 
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" name="description" class="form-control" id="description" placeholder="Description">
            </div>
            <div class="form-group">
                <label for="version">Version</label>
                <input name="version" type="text" class="form-control" id="version" placeholder="Version">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" >
                    <option value="">Select</option>
                    <option value="1">Pending</option>
                    <option value="2">Publish</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('admin.template.get.index') }}" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/additional-methods.min.js') }}"></script>
<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<script>
    function elFinderBrowser (callback, value, meta) {
        tinymce.activeEditor.windowManager.open({
            file: "{{ asset('tinymce/plugins/elfinder/elfinder.html') }}",// use an absolute path!
            title: 'elFinder 2.1',
            width: 900, 
            height: 450,
            resizable: 'yes'
        }, {
            oninsert: function (file, fm) {
                var url, reg, info;

                // URL normalization
                url = file.url;
                reg = /\/[^/]+?\/\.\.\//;
                while(url.match(reg)) {
                    url = url.replace(reg, '/');
                }
                
                // Make file info
                info = file.name + ' (' + fm.formatSize(file.size) + ')';

                // Provide file and text for the link dialog
                if (meta.filetype == 'file') {
                    callback(url, {text: info, title: info});
                }

                // Provide image and alt text for the image dialog
                if (meta.filetype == 'image') {
                    callback(url, {alt: info});
                }

                // Provide alternative source and posted for the media dialog
                if (meta.filetype == 'media') {
                    callback(url);
                }
            }
        });
return false;
}



    // TinyMCE init
    tinymce.init({
      selector: "#content",
      height : 500,
        // plugins: "table,code, image, link, media",
        relative_urls: false,
        remove_script_host: false,
        style_formats: [
        { title: 'Activitie', block: 'div', classes: 'activitie', styles: { color: '#0000' } },
        { title: 'Address', block: 'div', classes:'address', styles: { color: '#00000' } },
        { title: 'Availability', block: 'div', classes: 'availability', styles: { color: '#0000' } },
        { title: 'Education', block: 'div', classes: 'education', styles: { color: '#0000' } },
        { title: 'Email', block: 'div', classes: 'email', styles: { color: '#0000' } },
        { title: 'Infomation', block: 'div', classes: 'infomation', styles: { color: '#0000' } },
        { title: 'Qualification', block: 'div', classes: 'key_qualification', styles: { color: '#0000' } },
        { title: 'Linkedin', block: 'div', classes: 'linkedin', styles: { color: '#0000' } },
        { title: 'Mobile Phone number', block: 'div', classes: 'phone', styles: { color: '#0000' } },
        { title: 'Name', block: 'div', classes: 'name', styles: { color: '#000000' } },
        { title: 'Objective', block: 'div', classes: 'objective', styles: { color: '#0000' } },
        { title: 'Personal Test', block: 'div', classes: 'personal_test', styles: { color: '#0000' } },
        { title: 'Photo', block: 'div', classes: 'photo', styles: { color: '#0000' } },

        { title: 'Profile Website', block: 'div', classes: 'profile_website', styles: { color: '#0000' } },
        { title: 'Reference', block: 'div', classes: 'reference', styles: { color: '#0000' } },

        { title: 'Work Experience', block: 'div', classes: 'work', styles: { color: '#0000' } }
        ],
        // visualblocks_default_state: true,
        // end_container_on_empty_block: true,
        plugins: [
        " image,preview,hr,wordcount,code,table,colorpicker,textcolor"
        ],
        toolbar1: "newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect colorpicker|cut copy paste  | bullist numlist | outdent indent blockquote | undo redo | image code |  preview | forecolor backcolor |table | hr removeformat  | ltr rtl ",

        menubar :false,
        file_picker_callback : elFinderBrowser,

    });


$(function() {

    var isBusy = false;
    $('form').validate({
        rules: {
            title : {
                required: true,
            /*remote : {
                url: '{{ route("admin.template.check") }}',
                type: 'GET',
                data: {
                    title: function() {
                        return $( "#title" ).val();
                    }
                }   
            }*/
        },
        cat_id : {
            required : true
        },
        version : {
            required: true
        },
        status : {
            required : true
        }
    },
    messages : {
        title: {
            remote : 'Title exists, please change title'
        }
    },
    highlight: function(element) {
        $(element).closest('.form-group').addClass('has-error');
    },
    unhighlight: function(element) {
        $(element).closest('.form-group').removeClass('has-error');
    },
    errorElement: 'span',
    errorClass: 'help-block',
    errorPlacement: function(error, element) {
        if(element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    }
});
});


</script>
@endsection