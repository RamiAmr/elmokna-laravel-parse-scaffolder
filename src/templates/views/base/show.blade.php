<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Show</title>
    <!-- Styles -->
    {{--Styles--}}

</head>
<body>
{!! Form::model($item,['objectId'=>'form', 'method'=>'get','class'=>'' , 'action' =>[ 'TemplateController@edit',$item['objectId']]])!!}

{{--FORM-CONTENT-HERE--}}


<div class="">
    <div class="">
        {!! Form::submit('Edit',["class"=>""]) !!}
    </div>
</div>

{!! Form::close() !!}
</body>
</html>
