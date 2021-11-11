<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
  



//1
    public function getAllCat($parent_id=0)
    {
        //get all cat and children 
        
                $Main_Cat=DB::select('select * from categories where parent_id= '.$parent_id);
                $arr=[];
                foreach($Main_Cat as $subCat)
                {
                    $arr[]=[
                      'id'=>$subCat->id,
                      'parent_id'=>$subCat->parent_id,
                      'category_name'=>$subCat->name,
                      'child'=>$this->getAllCat($subCat->id)
                    ];  
                    
                    
               }

               
               return response()->json($arr, 200);
               
               
               

        } 





//2 by id get parent with children of children 
    public function getparent_child($id)
    {

       
                $main_cat=DB::select('select * from categories where id='.$id);
                $arr[]= ['MAinCat'=>$main_cat[0]->name,
                            'child'=>$this->sortMax2Min($id,$main_cat[0]->parent_id)
                           ];
                           return response()->json($arr, 200);

            }
   


           



            public function sortMax2Min($id,$parent_id)
            {
              //get all child and check if there's a loop 
                $Main_Cat=DB::select('select * from categories where parent_id= '.$id.' and id !='.$parent_id);
                
                  
                if($Main_Cat){

               
                $arr=[];
                foreach($Main_Cat as $subCat)
                {
                 
                    $arr[]=[
                      'id'=>$subCat->id,
                      'parent_id'=>$subCat->parent_id,
                      'category_name'=>$subCat->name,
                      'child'=>$this->sortMax2Min($subCat->id,$subCat->parent_id)
                    ];  
                    
                  
                  
               }
               
              
               return $arr;
              }else{
                return "there is a loop ";
              }
               
              
            }
            //3 by id to get recursive category to grand parent
            public function byChildId($id)
            {

              $child=DB::select('select * from categories where id='.$id);
                        $arr[]= ['child cat'=>$child[0]->name,
                                'cat name'=>$this->sortMin2Max($id,$child[0]->parent_id) 
                                ];
                                  return response()->json($arr, 200);
                        
                    }

            public function sortMin2Max($id,$parent_id)
            {
              //prevent conflict  of being the child are the parent of father 
                $child_cat=DB::select('select * from categories where id= '.$parent_id.' and parent_id !='.$id);
                if($child_cat){
                $arr=[];               
                foreach($child_cat as $subCat)
                {
                    $arr[]=[
                      'id'=>$subCat->id,
                      'parent_id'=>$subCat->parent_id,
                      'category_name'=>$subCat->name,
                      'parent'=>$this->sortMin2Max($subCat->id,$subCat->parent_id)
                    ];  
                    
                    
               }

               
              return $arr;
            }else{
              return "there is a loop ";
            }
            }



   
     
           
    
   

    
    
    }
