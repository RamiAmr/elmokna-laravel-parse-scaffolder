<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit</title>

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
    {{--Styles--}}

</head>
<body>
<div class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

    <div class="m-grid m-grid--hor m-grid--root m-page">

        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
            <div class="m-grid__item m-grid__item--fluid m-wrapper">
                <div class="m-content">
                    <div class="row">
                        <div class="col-md-6">


                            <div class="m-portlet m-portlet--tab">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">

                                        <div class="m-portlet__head-title">
                                            <span class="m-portlet__head-icon m--hide"><i class="la la-gear"></i></span>
                                            <h3 class="m-portlet__head-text">
                                                TITLE
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="m-portlet__head-tools">
                                        <ul class="m-portlet__nav">
                                            <li class="m-portlet__nav-item">
                                                <a href="INDEX-ACTION-HERE" class="m-portlet__nav-link btn btn-secondary m-btn m-btn--air m-btn--icon m-btn--icon-only m-btn--pill">
                                                    <i class="la la-home"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                {!! Form::model($item,['objectId'=>'form',"enctype"=>"multipart/form-data", 'method'=>'PATCH','class'=>'m-form m-form--fit m-form--label-align-right' , 'action' =>[ 'TemplateController@update',$item['objectId']], 'files' => true])!!}

                                <div class="m-portlet__body">

                                    {{--FORM-CONTENT-HERE--}}

                                </div>


                                <div class="m-portlet__foot m-portlet__foot--fit">
                                    <div class="m-form__actions">
                                        {!! Form::submit('Submit',["class"=>"btn btn-primary"]) !!}
                                        {!! Form::button('Delete',["class"=>"btn btn-danger" , "data-target"=>"#m_modal_5","data-toggle"=>"modal"]) !!}

                                        {!! Form::reset('Cancel',["class"=>"btn btn-secondary"]) !!}
                                    </div>
                                </div>

                                {!! Form::close() !!}

                            </div>

                        </div>
                    </div>


                    <!--begin::Modal-->
                    <div class="modal fade" id="m_modal_5" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                        Delete {{--TYPE-HERE--}}
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">
												&times;
											</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        Are You Sure You Want To Delete This {{--TYPE-HERE--}} ?
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        Close
                                    </button>

                                    {!! Form::model($item,['id'=>'form', 'method'=>'DELETE', 'action' =>[ 'TemplateController@destroy',$item['objectId']]])!!}
                                    {!! Form::submit('Confirm Delete',["class"=>"btn btn-danger"]) !!}
                                    {!! Form::close()!!}

                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Modal-->

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
