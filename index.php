<!DOCTYPE html>
<html lang="en">
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<script type="text/javascript" src="js/jquery.min.js"></script>
	

    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>JSI Membaca Hot Topic</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

		</style>
		
		<?php
// LOGIC
    set_include_path(get_include_path() . PATH_SEPARATOR . './lib/easyrdf/lib');
    require_once "EasyRdf.php";
    require_once "html_tag_helpers.php";
    // Setup some additional prefixes for DBpedia
    EasyRdf_Namespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
    EasyRdf_Namespace::set('konsep', 'http://tetha.com/konsep#');
    EasyRdf_Namespace::set('dimensi', 'http://tetha.com/dimensi#');
    EasyRdf_Namespace::set('qb', 'http://purl.org/linked-data/cube#');
    EasyRdf_Namespace::set('vocab', 'http://tetha.com/vocab#');
    EasyRdf_Namespace::set('kategori', 'http://tetha.com/kategori#');
    EasyRdf_Namespace::set('hangat', 'http://tetha.com/hangat#');
    EasyRdf_Namespace::set('news', 'http://tetha.com/news#');

    $sparql = new EasyRdf_Sparql_Client('http://localhost:3030/fp_tetha/sparql');
    $query = ' PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
prefix konsep:          <http://tetha.com/konsep#> 
prefix dimensi:         <http://tetha.com/dimensi#> 
prefix qb:              <http://purl.org/linked-data/cube#>
prefix tetha:           <http://tetha.com/datastatistik#> 
prefix vocab:           <http://tetha.com/vocab#> 
prefix kategori:        <http://tetha.com/kategori#> 
prefix hangat:          <http://tetha.com/hangat#>
prefix news:            <http://tetha.com/news#>


select ?KATEGORI ?HOTTEST ?JUMLAH {
  ?data dimensi:kategori ?kategori .
  ?kategori rdfs:label ?KATEGORI .
  ?data dimensi:hangat ?topik .
  ?topik rdfs:label ?HOTTEST .
  ?data tetha:jmlh_posting ?JUMLAH .
  
}
Limit 25

';
    $result = $sparql->query(
        $query
    );
    $no = 1;


?>
<script type="text/javascript">
$(function () {
    // Set up the chart
    var chart = new Highcharts.Chart({
        chart: {
            renderTo: 'overall',
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 15,
                beta: 15,
                depth: 50,
                viewDistance: 25
            },

        }, plotOptions: {
            xAxis: {
                colorByPoint: true
            }
        },

        title: {
            text: 'Jumlah Posting News Berdasarkan 7 Kategori'
        },
        subtitle: {
            text: 'Grafik ini menjelaskan jumlah posting dan hot issue per kategori '
        },
		xAxis: {
                        categories: [
						<?php
        foreach ($result as $row) {
                                echo "'".str_replace('http://localhost/vayvis/ns/gov/','',$row->KATEGORI)."',";
                            }?>
						
						]
                    },
        plotOptions: {
            column: {
                depth: 25
            }
        },
        series: [{name:'Total Tweets',
            data: [<?php
        foreach ($result as $row) {
            
                                echo "{name:'Hot Topic : ".str_replace('http://localhost/vayvis/ns/gov/','',$row->HOTTEST)."',";
                    
            echo "y:".str_replace(',','.',$row->JUMLAH).',},';
        }
        ?>

]
        }]
    }

    );


    function showValues() {
        $('#alpha-value').html(chart.options.chart.options3d.alpha);
        $('#beta-value').html(chart.options.chart.options3d.beta);
        $('#depth-value').html(chart.options.chart.options3d.depth);
    }

    // Activate the sliders
    $('#sliders input').on('input change', function () {
        chart.options.chart.options3d[this.id] = this.value;
        showValues();
        chart.redraw(false);
    });

    showValues();
});
		</script>
		

			
		</script>
	
  </head>
  <body>
  <script src="js/highcharts.js"></script>
<script src="js/highcharts-3d.js"></script>
<script src="js/modules/exporting.js"></script>
<script src="js/grouped-categories.js"></script>
	 <script type="text/javascript">
    document.getElementById("dash").setAttribute("class","active");
    </script>    
    <div class="container">
        <div class="page-header">
            <h1>JSI MEMBACA HOT NEWS</h1>
        </div>
    <div> </div>
    <div><h3>Visualisasi Data</h3></div>
    </div>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade in active" id="cost">
						
						<div class="jumbotron" id="overall">
						</div>
						<br>
						<h3>Permohonan Belas Kasihan Bu IIN</h3>
						<br></br>
						
	<table class="table table-striped table-bordered table-hover" id="dataTables-example">
        </table>
    <br></br>				
</div>
	<div role="tabpanel" class="tab-pane fade" id="total">
	<div class="jumbotron" id="overall2">					
	</div>				
	<table class="table table-striped table-bordered table-hover" id="dataTables-example">
        </tr>
      </thead>
    <tbody>
</tbody>
</table>
    </div>
    </div>
	   </div>
			<div class="col-md-4">
				<div id="sliders">
							<table>
								<tr>
									<td>Alpha Angle</td>
									<td><input id="alpha" type="range" min="0" max="45" value="15"/> <span id="alpha-value" class="value"></span></td>
								</tr>
								<tr>
									<td>Beta Angle</td>
									<td><input id="beta" type="range" min="-45" max="45" value="15"/> <span id="beta-value" class="value"></span></td>
								</tr>
								<tr>
									<td>Depth</td>
									<td><input id="depth" type="range" min="20" max="100" value="50"/> <span id="depth-value" class="value"></span></td>
								</tr>
							</table>
						</div>
			</div>
		</div>
	</div>

	
	<div id="container"></div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="api/js/jquery-1.11.3.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	
  </body>
</html>