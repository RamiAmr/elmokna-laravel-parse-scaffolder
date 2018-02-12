<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit</title>
    <!-- Styles -->
    {{--Styles--}}

</head>
<body>
{!! Form::model($item,['id'=>'form',"enctype"=>"multipart/form-data", 'method'=>'PATCH','class'=>'' , 'action' =>[ 'TemplateController@update',$item['objectId']], 'files' => true])!!}

{{--FORM-CONTENT-HERE--}}

<div class="">
    <div class="">
        {!! Form::submit('Submit',["class"=>""]) !!}
        {!! Form::reset('Cancel',["class"=>""]) !!}
    </div>
</div>
{!! Form::close() !!}

{!! Form::model($item,['id'=>'form_delete', 'method'=>'DELETE', 'action' =>[ 'TemplateController@destroy',$item['objectId']]])!!}
{!! Form::button('Delete',["class"=>""]) !!}
{!! Form::close()!!}
</body>
</html>
