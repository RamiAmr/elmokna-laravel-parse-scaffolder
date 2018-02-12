<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Index</title>

    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script type="text/javascript">
        WebFont.load({
            google: {"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]},
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>


{!! HTML::style(asset('assets/vendors/base/vendors.bundle.css')) !!}
{!! HTML::style(asset('assets/demo/default/base/style.bundle.css')) !!}

<!-- Styles -->
    {{--STYLES_HERE--}}
</head>
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

<div class="m-grid m-grid--hor m-grid--root m-page">

    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
        <div class="m-grid__item m-grid__item--fluid m-wrapper">
            <div class="m-content">
                <div class="m-portlet m-portlet--mobile">
                    <div class="m-portlet m-portlet--mobile">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text">
                                        TBD TITLE
                                    </h3>
                                </div>
                            </div>
                            <div class="m-portlet__head-tools">
                                <ul class="m-portlet__nav">
                                    <li class="m-portlet__nav-item">
                                        <a href="INDEX-ACTION-HERE"
                                           class="m-portlet__nav-link btn btn-secondary m-btn m-btn--air m-btn--icon m-btn--icon-only m-btn--pill">
                                            <i class="la la-home"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <!--begin: Search Form -->
                            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                                <div class="row align-items-center">
                                    <div class="col-xl-8 order-2 order-xl-1">
                                        <div class="form-group m-form__group row align-items-center">

                                            <div class="col-md-4">
                                                <div class="m-input-icon m-input-icon--left">
                                                    <input type="text" class="form-control m-input"
                                                           placeholder="Search..." id="generalSearch">
                                                    <span class="m-input-icon__icon m-input-icon__icon--left">
															<span>
																<i class="la la-search"></i>
															</span>
														</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                                        <a href="CREATE-ACTION-HERE"
                                           class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
												<span>
													<i class="la la-plus"></i>
													<span>
														New {{--CREATE-NAME-HERE--}}
													</span>
												</span>
                                        </a>
                                        <div class="m-separator m-separator--dashed d-xl-none"></div>
                                    </div>
                                </div>
                            </div>
                            <!--end: Search Form -->
                            <!--begin: Datatable -->
                            <table class="m-datatable" id="html_table" width="100%">

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
                                                <td data-field="Actions" class="m-datatable__cell"><span
                                                            style="overflow: visible; width: 110px;">

                                                         <a href="SHOW-ACTION-HERE"
                                                            class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                                            title="Show details"> <i class="la la-ellipsis-h"></i>
                                                        </a>

                                                        <a href="EDIT-ACTION-HERE"
                                                           class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                                           title="Edit details"> <i class="la la-edit"></i>
                                                        </a>
                                                        {{--<a href="DELETE-ACTION-HERE"
                                                           class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                                           data-target="#m_modal_5" data-toggle="modal"
                                                           title="Delete"> <i class="la la-trash"></i> </a> --}}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif
                                </tbody>


                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</div>

<!-- begin::Quick Nav -->
<!--begin::Base Scripts -->
<script src="{{asset("assets/vendors/base/vendors.bundle.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/demo/default/base/scripts.bundle.js")}}" type="text/javascript"></script>
<!--end::Base Scripts -->
<!--begin::Page Resources -->
<script src="{{asset("assets/demo/default/custom/components/datatables/base/html-table.js")}}"
        type="text/javascript"></script>
<!--end::Page Resources -->

</body>
</html>
