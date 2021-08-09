@extends('backend/layout')
@section('content')
<section class="content-header">
    <h1>Company</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">{{ $company->page_title }}</li>
    </ol>
</section>
<!-- Main content -->
<section id="main-content" class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{ $company->page_title }}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    {{ Form::open(array('route' => $company->form_action, 'method' => 'POST', 'files' => true,'id' => 'company-form')) }}
                    {{ Form::hidden('id', $company->id, array('id' => 'company_id')) }}
                    <!-- All the input forms are encoded down below -->
                    <div id="form-name" class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                            <span class="label label-danger label-required">Required</span>
                            <strong class="field-title">Name</strong>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                            {{ Form::text('name', $company->name, array('class' => 'form-control validate[required, email, regex[/^[\w-]*$/], alpha_num, maxSize[255]]', 'data-prompt-position' => 'bottomLeft:0,11')) }}
                        </div>
                    </div>

                    <div id="form-email" class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                            <span class="label label-danger label-required">Required</span>
                            <strong class="field-title">Email</strong>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                            {{ Form::email('email', $company->email, array('placeholder' => '', 'class' => 'form-control validate[required, maxSize[255]]', 'data-prompt-position' => 'bottomLeft:0,11')) }}
                        </div>
                    </div>

                    
                    <div id="form-postcode" class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                            <span class="label label-danger label-required">Required</span>
                            <strong class="field-title">Postcode</strong>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content" style="display:inline;margin-left:77px;">
                            {{ Form::text('postcode',$company->postcode, array('placeholder' => ' ','onchange'=>"eraseMessage(this)",'id' => 'postcode', 'class' => 'form-control validate[required, 
                            minSize[6], maxSize[8]]', 'data-prompt-position' => 'bottomLeft:0,11')) }}
                        </div>   
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-content" style="display:inline">
                            <button type="button" id="postcode_button" onclick="fillLocation();" class="btn btn-primary">Search</button>
                            <p id="postcode_text" style="color:Tomato;"></p> 
                        </div>
                    </div>

                    
                    <div id="form-prefecture" class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                            <span class="label label-danger label-required">Required</span>
                            <strong class="field-title">Prefecture</strong>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                            {{ Form::select('prefecture_id', $prefecture_list,$company->prefecture_id,array('class' => 'form-control m-bot15','id' => 'prefecture')) }}
                        
                        </div>
                    </div>
                    
                    <div id="form-city" class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                            <span class="label label-danger label-required">Required</span>
                            <strong class="field-title">City</strong>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                            {{ Form::text('city',$company->city, array('placeholder' => ' ','id' => 'city', 'class' => 'form-control validate[required, minSize[0], maxSize[255]]', 'data-prompt-position' => 'bottomLeft:0,11')) }}
                        </div>
                    </div>
                    
                    <div id="form-local" class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                            <span class="label label-danger label-required">Required</span>
                            <strong class="field-title">Local</strong>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                            {{ Form::text('local',$company->local, array('placeholder' => ' ','id' => 'local', 'class' => 'form-control validate[required, minSize[1], maxSize[255]]', 'data-prompt-position' => 'bottomLeft:0,11')) }}
                        </div>
                    </div>
                    
                    <div id="form-street_address" class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                            <strong class="field-title">Street address</strong>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                            {{ Form::text('street_address',$company->street_address, array('placeholder' => ' ', 'class' => 'form-control validate[ minSize[0], maxSize[255]]', 'data-prompt-position' => 'bottomLeft:0,11')) }}
                        </div>
                    </div>
                    
                    <div id="form-business_hours" class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                            <strong class="field-title">Business hours</strong>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                            {{ Form::text('business_hours',$company->business_hours, array('placeholder' => ' ', 'class' => 'form-control validate[ minSize[0], maxSize[255]]', 'data-prompt-position' => 'bottomLeft:0,11')) }}
                        </div>
                    </div>                    
                    
                    <div id="form-phone" class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                            <strong class="field-title">Phone</strong>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                            {{ Form::text('phone',$company->phone, array('placeholder' => ' ', 'class' => 'form-control validate[ minSize[0], maxSize[255]]', 'data-prompt-position' => 'bottomLeft:0,11')) }}
                        </div>
                    </div>
                    
                    <div id="form-fax" class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                            <strong class="field-title">Fax</strong>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                            {{ Form::text('fax',$company->fax, array('placeholder' => ' ', 'class' => 'form-control validate[minSize[0], maxSize[255]]', 'data-prompt-position' => 'bottomLeft:0,11')) }}
                        </div>
                    </div>
                    
                    <div id="form-url" class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                            <strong class="field-title">URL</strong>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                            {{ Form::text('url',$company->url, array('placeholder' => ' ', 'class' => 'form-control validate[minSize[0], maxSize[255]]', 'data-prompt-position' => 'bottomLeft:0,11')) }}
                        </div>
                    </div>
                    
                    <div id="form-license_number" class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                            <strong class="field-title">License Number</strong>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                            {{ Form::text('license_number',$company->license_number, array('placeholder' => ' ', 'class' => 'form-control validate[minSize[0], maxSize[255]]', 'data-prompt-position' => 'bottomLeft:0,11')) }}
                        </div>
                    </div>
                    
                    <div id="form-image" class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
                            <span class="label label-danger label-required">Required</span>
                            <strong class="field-title">Image</strong>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
                            <input type="file" name="image" id="image" onchange="loadPreview(this);" accept="image/*" placeholder="Choose file"/> 
                            <!-- When editing a company, the picture should be loaded -->                                                                               
			    @if($company->page_type == 'create')
			    <p id="upload_text" style="color:Tomato;">ファイルをアップロードして下さい（推奨サイズ：1280 x 720;5MBまで）</p> 
                            <img id="preview_image" src="{{ asset('img/no-image/no-image.jpg') }}" alt="preview image" style="max-height: 150px;">
                            @elseif(pathinfo($company->image)['extension'] == "jpg")
                            <p id="upload_text" style="color:Tomato;">画像の変更を希望する場合は、ファイルをお選びください</p> 
                            <img id="preview_image" src="{{ asset('uploads/files/company_'.$company->id.'.jpeg') }}" alt="preview image" style="max-height: 150px;">
                            @else
                            <p id="upload_text" style="color:Tomato;">画像の変更を希望する場合は、ファイルをお選びください</p> 
                            <img id="preview_image" src="{{ asset('uploads/files/company_'.$company->id.'.'.pathinfo($company->image)['extension']) }}" alt="preview image" style="max-height: 150px;">
                            @endif                           
                        </div>
                    </div>
                    

                    
                    <div id="form-button" class="form-group no-border">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center" style="margin-top: 20px;">
                            <button type="submit" name="submit" id="send" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
@endsection

@section('title', 'Company | ' . env('APP_NAME',''))

@section('body-class', 'custom-select')

@section('css-scripts')
@endsection

@section('js-scripts')
<script src="{{ asset('bower_components/bootstrap/js/tooltip.js') }}"></script>
<!-- validationEngine -->
<script src="{{ asset('js/3rdparty/validation-engine/jquery.validationEngine-en.js') }}"></script>
<script src="{{ asset('js/3rdparty/validation-engine/jquery.validationEngine.js') }}"></script>
<script src="{{ asset('js/backend/companies/form.js') }}"></script>
@endsection
