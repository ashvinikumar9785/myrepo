<?php
namespace App\Lib;
/**
 * 
 * This Library use for helper.
 * 
 **/

class Helper
{
    
    public static function getStatus($current_status,$id,$route){
		 $html = '';
			  switch ($current_status) {
				  case '1':
					   $html =  '<span class="f-left margin-r-5 1"  data-link="'.$route.'" id="status_'.$id.'"><a href="javascript:void(0)" class="btn btn-xs btn-success" title="Active" onClick="changeStatus('.$id.')" >Active</a></span>';
					  break;
					  case '2':
					   $html =  '<span class="f-left margin-r-5 2"  data-link="'.$route.'" id="status_'.$id.'"><a href="javascript:void(0)" class="btn btn-danger btn-xs" title="Inactive" onClick="changeStatus('.$id.')" >UnVerified</a></span>';
					  break;
					  case '0':
					   $html =  '<span class="f-left margin-r-5 0"  data-link="'.$route.'" id="status_'.$id.'"><a href="javascript:void(0)" class="btn btn-danger btn-xs" title="Inactive" onClick="changeStatus('.$id.')" >InActive</a></span>';
					  break; 
				  default: 
					  break;
			  }
		  return $html;
    }

     
    
    public static function getButtons($array = []) {
		
        $btn = [
            "Edit" => "<span class='f-left margin-r-5'><a data-toggle='tooltip'  class='btn btn-primary btn-xs' title='Edit' href='LINK'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a></span>&nbsp;",
            "Active" => '<span class="f-left margin-r-5"> <a class="btn btn-success btn-xs" title="Active" href="LINK"><i class="fa fa-check" aria-hidden="true"></i></a></span>&nbsp;',
            "Inactive" => '<span class="f-left margin-r-5"> <a class="btn btn-warning btn-xs" title="Inactive" href="LINK"><i class="fa fa-times" aria-hidden="true"></i></a></span>&nbsp;',
            "Delete" => '<span><a href="javascript:void(0)" id="delete_LINK" onclick="confirm_delete(LINK);" title="Delete" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" aria-hidden="true"></i></a></span>&nbsp;',
            "View" => '<span class="f-left margin-r-5"><a  class="btn btn-info btn-xs" title="View" href="LINK"><i class="fa fa-eye" aria-hidden="true"></i></a></span>&nbsp;'
        ];		
        $html = '';
        foreach($array as $arr)
        {
			$html  .= str_replace("LINK",$arr['link'],$btn[$arr['key']]);
        }
        return $html;
    }    


    public static function getImage($url,$height=null,$width=null){
        if($url && file_exists($url)){
    	   return "<img src='".url($url)."' height='$height' width='$width' />";
        }else{
            return "N/A";
        }
    }
    
    

    
}
