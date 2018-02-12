<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create</title>
    <!-- Styles -->
    {{--Styles--}}

</head>
<body>
{!! Form::open([ 'id'=>'form',"enctype"=>"multipart/form-data", 'method'=>'POST','class'=>'' ,'action' => 'TemplateController@store', 'files' =>true])!!}

{{--FORM-CONTENT-HERE--}}

<div class="">
    <div class="">
        {!! Form::submit('Submit',["class"=>""]) !!}
        {!! Form::reset('Cancel',["class"=>""]) !!}
    </div>
</div>

{!! Form::close() !!}


</body>
</html>
