<script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );

  $( function() {
    $( "#datepicker2" ).datepicker();
  } );
  </script>

<div class="row">
<div id="div_1" class="container">
 <form action="" method="post" name="list_farms">

 
  	<h5 class="header center boutique section-title">List of farms</h5>
  

     <table style="border-spacing: 0px; width:100%; margin-left:auto;margin-right:auto;">
     <!-- Farms -->
     <thead>
     <tr>
      <th>
          Id 
     </th>
    
     <th>
          Name
     </th>
    
     <th>
          Location
     </th>

     <th>
          &nbsp;
     </th>
     
     <th style="align:center">
           Established
     </th>
     
    </tr>
    </thead>

    <tbody>
  
    <?php
         
     // list of farms
     if (isset($listAllFarms)) // Checking if the array exists and contains value
     {      
   
     $i=0;     
        foreach ($listAllFarms as $farms) 
        {                 
          
          $style="height: 5px; background-color: #D3D3D3";    
          $result1 =($i % 2);
          if ($result1==True)       $style  ="height: 5px; background-color: #FFFFFF"; 
          $my_id    =0;   
          if(isset($farms->id)) $my_id  = $farms->id; 
       
          $linkFarmDetails=$link_farms."&action=details"."&view=".base64_encode($my_id); 
          $linkFarmLocation=$link_farms."&action=location"."&view=".base64_encode($my_id); 
    ?>
      <tr style="<?php if (isset($style)) echo $style; ?> ">
        <td style="height:3px; border-radius:0px;" width="50" colspan="1">
          <a title="click to have statistics" href="<?php echo $linkFarmDetails; ?>" style="font-weight: bold; color:#000;text-decoration: underline">
          <strong>
           <?php 
              $prefix    ='1000000'; 
              
              if (strlen('100000')>strlen($farms->id)){
               $nbr_diff =strlen('100000')-strlen($farms->id); 
               $prefix   =substr($prefix, 0, (strlen('100000')-1));    
               echo $prefix.$farms->id;
              }else{
                echo $farms->id;
              }
           ?>
          </strong> 
          </a> 
        </td>

        <td>
          <?php if (isset($farms->name)) echo $farms->name; ?>
        </td>

        <td style="align:right">
          <b>
            <?php if (isset($farms->location)) echo $farms->location; ?>       
            </b>  
        </td>


         
        <td style="align:left"> 
        <!--
         <a title="See my location" href="<?php if(isset($linkFarmLocation)) echo $linkFarmLocation; ?>" style="font-weight: bold; color:#000;text-decoration: underline">
          <img  title="My location" width="25" height="20"  src="images/location_icon_nobg.png"/>
         </a>
            -->
        </td>
            

        <td>
         <?php 
          
          // Information on my farms  
          $farms_details  = Array();
          $date           = null;     
          if(isset($farms->id) && $farms->id>0) {           
               
            if(isset($farms->established)) {               
              $date = $farms->established;
            }
          }
          
          if (isset($date) && $date!=null) {
              echo strftime("%a, %d %b %g - %Hh %M", strtotime($date)); 
          }
          
         ?>
        </td>

 
       </tr>

        <?php    
         $i++;      
      }        
    } 
   ?>
    </tbody>
   </table>
  </form>

</div>
</div>
