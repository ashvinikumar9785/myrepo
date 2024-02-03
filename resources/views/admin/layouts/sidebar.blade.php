<?php
$currentAction = \Route::currentRouteAction();
list($controller, $action) = explode('@', $currentAction);
$controller = preg_replace('/.*\\\/', '', $controller);
?>
<aside class="left-sidebar" data-sidebarbg="skin5">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="p-t-30">
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.home')}}" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a></li>

                


                <?php $active = ($controller=="SocietyOwnerController" &&in_array($action,['index','add']) )?"active selected":""; ?>
                <li class="sidebar-item {{$active}}"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.society-owner.index')}}" aria-expanded="false"><i class="mdi mdi-airplay"></i><span class="hide-menu">Society Owner</span></a></li>

                <?php $active = ($controller=="EmailTemplateController" &&in_array($action,['index','add']) )?"active selected":""; ?>
                <li class="sidebar-item {{$active}}"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.emailtemplates.index')}}" aria-expanded="false"><i class="mdi mdi-airplay"></i><span class="hide-menu">Email Template </span></a></li>



                <!-- <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-alert"></i><span class="hide-menu">Errors </span></a>
                <ul aria-expanded="false" class="collapse  first-level">
                    <li class="sidebar-item"><a href="error-403.html" class="sidebar-link"><i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Error 403 </span></a></li>
                    <li class="sidebar-item"><a href="error-404.html" class="sidebar-link"><i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Error 404 </span></a></li>
                    <li class="sidebar-item"><a href="error-405.html" class="sidebar-link"><i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Error 405 </span></a></li>
                    <li class="sidebar-item"><a href="error-500.html" class="sidebar-link"><i class="mdi mdi-alert-octagon"></i><span class="hide-menu"> Error 500 </span></a></li>
                </ul>
            </li> -->
        </ul>
    </nav>
    <!-- End Sidebar navigation -->
</div>
<!-- End Sidebar scroll-->
</aside>