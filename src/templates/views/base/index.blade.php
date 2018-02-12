<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Index</title>

    <!-- Styles -->
    <style>
        tr td{
            text-align: center;
        }
    </style>
</head>
<body class="">

<table class="" id="html_table" width="100%">

    <thead>
    <tr>
        {{--TABLE-HEAD-HERE--}}
        <th>Actions</th>
    </tr>
    </thead>

    <tbody>
    @if(isset($json_data) && array_key_exists("status_code",$json_data) && $json_data["status_code"]==200 )

        @if(array_key_exists("data",$json_data))
            @foreach($json_data["data"] as $json_item)
                <tr>
                    {{--TABLE-CONTENT-HERE--}}
                    <td class="">
                        <span style="overflow: visible; width: 110px;">

                            <a href="SHOW-ACTION-HERE" class="" title="Show details"> Details </a>

                            <a href="EDIT-ACTION-HERE" class="" title="Edit details"> Edit </a>
                        </span>
                    </td>
                </tr>
            @endforeach
        @endif
    @endif
    </tbody>


</table>

</body>
</html>
