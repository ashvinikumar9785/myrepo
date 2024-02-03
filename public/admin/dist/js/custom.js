$(function() {
    "use strict";

    $(".preloader").fadeOut();
    $('.dropify').dropify();

    // ============================================================== 
    // Theme options
    // ==============================================================     
    // ============================================================== 
    // sidebar-hover
    // ==============================================================


    $(".left-sidebar").hover(
        function() {
            $(".navbar-header").addClass("expand-logo");
        },
        function() {
            $(".navbar-header").removeClass("expand-logo");
        }
    );
    // this is for close icon when navigation open in mobile view
    $(".nav-toggler").on('click', function() {
        $("#main-wrapper").toggleClass("show-sidebar");
        $(".nav-toggler i").toggleClass("ti-menu");
    });
    $(".nav-lock").on('click', function() {
        $("body").toggleClass("lock-nav");
        $(".nav-lock i").toggleClass("mdi-toggle-switch-off");
        $("body, .page-wrapper").trigger("resize");
    });
    $(".search-box a, .search-box .app-search .srh-btn").on('click', function() {
        $(".app-search").toggle(200);
        $(".app-search input").focus();
    });

    // ============================================================== 
    // Right sidebar options
    // ==============================================================
    $(function() {
        $(".service-panel-toggle").on('click', function() {
            $(".customizer").toggleClass('show-service-panel');

        });
        $('.page-wrapper').on('click', function() {
            $(".customizer").removeClass('show-service-panel');
        });
    });
    // ============================================================== 
    // This is for the floating labels
    // ============================================================== 
    $('.floating-labels .form-control').on('focus blur', function(e) {
        $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
    }).trigger('blur');

    // ============================================================== 
    //tooltip
    // ============================================================== 
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
    // ============================================================== 
    //Popover
    // ============================================================== 
    $(function() {
        $('[data-toggle="popover"]').popover()
    })

    // ============================================================== 
    // Perfact scrollbar
    // ============================================================== 
    /*$('.message-center, .customizer-body, .scrollable').perfectScrollbar({
        wheelPropagation: !0
    });*/

    /*var ps = new PerfectScrollbar('.message-body');
    var ps = new PerfectScrollbar('.notifications');
    var ps = new PerfectScrollbar('.scroll-sidebar');
    var ps = new PerfectScrollbar('.customizer-body');*/

    // ============================================================== 
    // Resize all elements
    // ============================================================== 
    $("body, .page-wrapper").trigger("resize");
    $(".page-wrapper").delay(20).show();
    // ============================================================== 
    // To do list
    // ============================================================== 
    $(".list-task li label").click(function() {
        $(this).toggleClass("task-done");
    });

    //****************************
    /* This is for the mini-sidebar if width is less then 1170*/
    //**************************** 
    var setsidebartype = function() {
        var width = (window.innerWidth > 0) ? window.innerWidth : this.screen.width;
        if (width < 1170) {
            $("#main-wrapper").attr("data-sidebartype", "mini-sidebar");
        } else {
            $("#main-wrapper").attr("data-sidebartype", "full");
        }
    };
    $(window).ready(setsidebartype);
    $(window).on("resize", setsidebartype);
    //****************************
    /* This is for sidebartoggler*/
    //****************************
    $('.sidebartoggler').on("click", function() {
        $("#main-wrapper").toggleClass("mini-sidebar");
        if ($("#main-wrapper").hasClass("mini-sidebar")) {
            $(".sidebartoggler").prop("checked", !0);
            $("#main-wrapper").attr("data-sidebartype", "mini-sidebar");
        } else {
            $(".sidebartoggler").prop("checked", !1);
            $("#main-wrapper").attr("data-sidebartype", "full");
        }
    }); 

});
function Alert(text,type=true){
    var actionTextColor = '#219864';
    if(type==false){
        actionTextColor = '#C04522';
    } 
    Snackbar.show({text: text,showAction:true,pos:'bottom-center',actionTextColor:actionTextColor,duration:30000});
} 
function handleError(error){
    if(error.status === 422){
      var response = JSON.parse(error.responseText); 
      $.each(response.errors, function (k, v) {
          $("body #"+k).after("<div class='text-danger error'>"+v+"</div>");
          $("body #"+k).focus();
      }); 
    }else{
        Alert(error.statusText,false);
    }
}

function beforeSubmit(button){
    $("#"+button).attr('disabled',true);
}

function disable(btn,type){ 
    if(type==true){
        $("body "+btn).attr('disabled',true);
        $("body #licon").html('<i class="fa fa-spinner fa-spin"></i> ');
    }else{
        $("body  "+btn).removeAttr('disabled');
        $("body #licon").html('');
    }
}

function enable(btn,text){ 
    $("body "+btn).removeAttr('disabled');
    // $(btn).html(text);
    $("body #licon").html('');
}

function convertToSlug(Text) {
    return Text
        .toLowerCase()
        .replace(/ /g, '-')
        .replace(/[^\w-]+/g, '');
}

function convertToName(Text) {
    return Text
        .toLowerCase()
        .replace(/[^\w ]+/g,'')
        .replace(/ +/g,'-');
}

function changeStatus(id)
{     
    $.ajax({
        url: $("#status_"+id).attr('data-link'),
        type: 'POST',
        data:{id:id},
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend:function(){
            $("#status_"+id).html('<i class="fa fa-spinner fa-spin"></i>');
        },
        error:function(error){
            Alert(error.statusText,false);
            $("#status_"+id).html(response);     
        },
        success: function (response) {
            $("#status_"+id).html(response);
        }
    });
}

function confirm_delete(id){      
    if(id!="" && confirm("Are you sure?")){
        $.ajax({
            url: $("#delete_"+id).attr("data-link"),
            type: 'POST',
            data:{id:id},
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend:function(){
                $("#delete_"+id+ " i").removeClass('fa-trash'); 
                $("#delete_"+id+ " i").addClass('fa-spinner fa-spin'); 
                $("#delete_"+id).addClass('disabled'); 
            },
            success: function (response) {
                if(response.status=='true'){
                    $("#delete_"+id).parents("tr").remove();
                    Alert(response.message,true);
                }else{
                    Alert(response.message,false);
                    $("#delete_"+id+ " i").addClass('fa-trash'); 
                    $("#delete_"+id+ " i").removeClass('fa-spinner fa-spin'); 
                    $("#delete_"+id).removeClass('disabled'); 
                }
            },error:function(error){
                Alert(error.statusText,false);
                $("#delete_"+id+ " i").addClass('fa-trash'); 
                $("#delete_"+id+ " i").removeClass('fa-spinner fa-spin'); 
                $("#delete_"+id).removeClass('disabled'); 
            }
        });
    }
}

function toggleSidebar(link){
     $.ajax({
        url: link,
        type: 'GET',  
        success: function (response) {
            if(response.status=='false'){
                Alert(response.message,false);
            }
        },error:function(error){
            Alert(error.statusText,false);
        }
    });
}