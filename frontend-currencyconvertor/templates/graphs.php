<div class="row">
<div id="div_1" class="container">
 <form action="" method="post" name="search_trans">

  <h5 class="header center boutique section-title">Graphs(Rainfall/monthly)</h5>

 <div class="row">
      <div class="input-field col s6" style="width:150px">      
        <label style="color:#000000"><strong>Y Axis(Vertical) :</strong></label> 
      </div>
      <div class="input-field col s6" style="width:250px">      
       <label style="color:#000000">Rainfall(monthly median) </label>
      </div>
  </div>     

  <div class="row">
      <div class="input-field col s6" style="width:150px">      
        <label style="color:#000000"><strong>X Axis(Horizontal):</strong></label> 
      </div>
      <div class="input-field col s6" style="width:250px">      
        <label style="color:#000000"> Months</label>
      </div>
  </div>     

  <div class="row">&nbsp;
  </div>   

 <div class="row">
      <div class="input-field col s6" style="width:50px; background-color: #59922b">&nbsp;</div>

      <div class="input-field col s6" style="width:210px">     
        <a title="See my location" target="_blank" href="#" style="font-weight: bold; color:#000;text-decoration: underline">          
              Friman Metsola Collective
        </a>      
        
      </div>

  

      <div class="input-field col s6" style="width:50px; margin-left:20px; background-color: #f05b4f">&nbsp;</div>
      <div class="input-field col s6" style="width:180px">  
        <a title="See my location" target="_blank" href="#" style="font-weight: bold; color:#000;text-decoration: underline">          
              PartialTech Research         
        </a>      
      </div>

      <div class="input-field col s6" style="width:50px; margin-left:20px; background-color: #f4c63d">&nbsp;</div>
      <div class="input-field col s6" style="width:30px">      
        <a title="See my location" target="_blank" href="#" style="font-weight: bold; color:#000;text-decoration: underline">          
          Noora
        </a>  
      </div>

      <div class="input-field col s6" style="width:50px; margin-left:50px; background-color: #d2691e">&nbsp;</div>
      <div class="input-field col s6" style="width:190px">      
        <a  title="See my location" target="_blank" href="#" style="font-weight: bold; color:#000;text-decoration: underline">          
          Organic Ossi's Impact
        </a>
      </div>

      

 </div>  

 <div class="row">&nbsp;</div> 

 <div class="row">
 <div class="ct-chart ct-perfect-fourth">

  <script src="bower_components/chartist/dist/chartist.js"></script>
   <script>
   
   

// passing php data to javascript code 
 var data1  = <?php echo json_encode($test_data_label1, JSON_HEX_TAG); ?>; // months x (axis)
 var data2  = <?php echo json_encode($testDataSerie, JSON_HEX_TAG); ?>; // numbers y (axis)
 var origin = <?php echo json_encode($testDataLow, JSON_HEX_TAG); ?>;   // origin of the graph



var chart = new Chartist.Line('.ct-chart', {
  //labels: represente x axis, we suppose we are in this current year 2020
  //  1st serie : Friman Metsola Collective
  //  2nd serie : PartialTech Research
  //  3rd serie : Noora
  //  4rd serie : Organic Ossi's Impact
 
  labels: data1,
  series: data2}, {
  low: origin});


// Let's put a sequence number aside so we can use it in the event callbacks
/*
var seq = 0,
  delays = 80,
  durations = 500;
*/
var seq = 0,
  delays = 10,
  durations = 3000;
// Once the chart is fully created we reset the sequence
chart.on('created', function() {
  seq = 0;
});

// On each drawn element by Chartist we use the Chartist.Svg API to trigger SMIL animations
chart.on('draw', function(data) {
  seq++;

  if(data.type === 'line') {
    // If the drawn element is a line we do a simple opacity fade in. This could also be achieved using CSS3 animations.
    data.element.animate({
      opacity: {
        // The delay when we like to start the animation
        begin: seq * delays + 3000,
        // Duration of the animation
        dur: durations,
        // The value where the animation should start
        from: 0,
        // The value where it should end
        to: 1
      }
    });
  } else if(data.type === 'label' && data.axis === 'x') {
    data.element.animate({
      y: {
        begin: seq * delays,
        dur: durations,
        from: data.y + 100,
        to: data.y,
        // We can specify an easing function from Chartist.Svg.Easing
        easing: 'easeOutQuart'
      }
    });
  } else if(data.type === 'label' && data.axis === 'y') {
    data.element.animate({
      x: {
        begin: seq * delays,
        dur: durations,
        from: data.x - 100,
        to: data.x,
        easing: 'easeOutQuart'
      }
    });
  } else if(data.type === 'point') {
    data.element.animate({
      x1: {
        begin: seq * delays,
        dur: durations,
        from: data.x - 10,
        to: data.x,
        easing: 'easeOutQuart'
      },
      x2: {
        begin: seq * delays,
        dur: durations,
        from: data.x - 10,
        to: data.x,
        easing: 'easeOutQuart'
      },
      opacity: {
        begin: seq * delays,
        dur: durations,
        from: 0,
        to: 1,
        easing: 'easeOutQuart'
      }
    });
  } else if(data.type === 'grid') {
    // Using data.axis we get x or y which we can use to construct our animation definition objects
    var pos1Animation = {
      begin: seq * delays,
      dur: durations,
      from: data[data.axis.units.pos + '1'] - 30,
      to: data[data.axis.units.pos + '1'],
      easing: 'easeOutQuart'
    };

    var pos2Animation = {
      begin: seq * delays,
      dur: durations,
      from: data[data.axis.units.pos + '2'] - 100,
      to: data[data.axis.units.pos + '2'],
      easing: 'easeOutQuart'
    };

    var animations = {};
    animations[data.axis.units.pos + '1'] = pos1Animation;
    animations[data.axis.units.pos + '2'] = pos2Animation;
    animations['opacity'] = {
      begin: seq * delays,
      dur: durations,
      from: 0,
      to: 1,
      easing: 'easeOutQuart'
    };

    data.element.animate(animations);
  }
});

// For the sake of the example we update the chart every time it's created with a delay of 10 seconds
chart.on('created', function() {
  if(window.__exampleAnimateTimeout) {
    clearTimeout(window.__exampleAnimateTimeout);
    window.__exampleAnimateTimeout = null;
  }
  window.__exampleAnimateTimeout = setTimeout(chart.update.bind(chart), 12000);
}); 
   </script>
  </div>
 </div>


 <!--
 <div class="row">
    <div class="input-field col s6">
      <input class="btn" type="submit" name="paiement_method" value="Search" >
    </div>  
 </div>
-->
  </form>

</div>
</div>

