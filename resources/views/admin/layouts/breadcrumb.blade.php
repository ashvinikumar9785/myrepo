<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title">{{isset($title)?$title:''}}</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li> 
                        <?php if(isset($breadcrumbs) && count($breadcrumbs)>0){ ?>
                            <?php  foreach($breadcrumbs as $breadcrumb){ ?>
                                <?php if($breadcrumb['relation']=="link"){?>
                                    <li class="breadcrumb-item"><a href="{{$breadcrumb['url']}}">{{$breadcrumb['name']}}</a></li>
                                <?php }else{ ?>
                                    <li class="breadcrumb-item active">{{$breadcrumb['name']}}</li>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>