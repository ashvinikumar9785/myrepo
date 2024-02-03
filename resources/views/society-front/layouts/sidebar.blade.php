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
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('society.home')}}" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a></li>

                 <?php $active = ($controller=="SocietyMemberController" &&in_array($action,['index','add']) )?"active selected":""; ?>
                <li class="sidebar-item {{$active}}"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('front.society-member.index')}}" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="hide-menu">Member</span></a></li>


                 <?php $active = ($controller=="NewsUpdateController" &&in_array($action,['index','add']) )?"active selected":""; ?>
                <li class="sidebar-item {{$active}}"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('front.news-updates.index')}}" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="hide-menu">News and Updates</span></a></li>


                <?php $active = ($controller=="FeedBackController" &&in_array($action,['index','add']) )?"active selected":""; ?>
                <li class="sidebar-item {{$active}}"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('front.suggestion-feedback.index')}}" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="hide-menu">FeedBack/Suggestion</span></a></li>


                <?php $active = ($controller=="PageController" && in_array($action,['index','add']) )?"active selected":""; ?>
                <li class="sidebar-item {{$active}}"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('society.pages.index')}}" aria-expanded="false"><i class="mdi mdi-page-layout-body"></i><span class="hide-menu">Pages</span></a></li>



                <?php $active = ($controller=="BannerController" && in_array($action,['index','add']) )?"active selected":""; ?>
                <li class="sidebar-item {{$active}}"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('society.banners.index')}}" aria-expanded="false"><i class="mdi mdi-page-layout-body"></i><span class="hide-menu">Banner</span></a></li>


                  <?php $active = ($controller=="EventController" && in_array($action,['index','add','view']) )?"active selected":""; ?>
                <li class="sidebar-item {{$active}}"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('front.events.index')}}" aria-expanded="false"><i class="mdi mdi-page-layout-body"></i><span class="hide-menu">Events</span></a></li>


                 <?php $active = ($controller=="SocietyChargesController" && in_array($action,['index','add','view']) )?"active selected":""; ?>
                <li class="sidebar-item {{$active}}"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('society.society-charges.index')}}" aria-expanded="false"><i class="mdi mdi-page-layout-body"></i><span class="hide-menu">Society Charges</span></a></li>


                 <?php $active = ($controller=="TransactionController" && in_array($action,['index','add','view']) )?"active selected":""; ?>
                <li class="sidebar-item {{$active}}"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('front.transactions.index')}}" aria-expanded="fal$se"><i class="mdi mdi-page-layout-body"></i><span class="hide-menu">Transactions </span><span style="color:red; font-weight: bold;">  &nbsp;&nbsp;{{@$count}}</span></a></li>
        </ul>
    </nav>
    <!-- End Sidebar navigation -->
</div>
<!-- End Sidebar scroll-->
</aside>