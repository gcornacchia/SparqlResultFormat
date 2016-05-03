<?php

class SparqlResultFormatGraph extends SparqlResultFormatBase implements SparqlFormat{
	
		function __construct() {
		$this->name = wfMessage("sprf.format.graph.title");
		$this->description = wfMessage("sprf.format.graph.description");
       $this->params = array(
			"divId" => array(
					"mandatory" => true,
					"description" => wfMessage("sprf.param.divId")
				),
			"sparqlEndpoint" => array(
					"mandatory" => true,
					"description" => wfMessage("sprf.param.sparqlEndpoint")
				),
			"sparqlEscapedQuery" => array(
					"mandatory" => true,
					"description" => wfMessage("sprf.param.sparqlEscapedQuery")
				),
			"divStyle" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.divStyle")
				),
			"spinnerImagePath" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.spinnerImagePath")
				),
			"divCssClass" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.divCssClass")
				),
			"nodeConfiguration" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.nodeConfiguration1").wfMessage("sprf.param.nodeConfiguration2"),
					"example" => "|nodeConfiguration=[{ category:\"Application\",nodeColor:\"#000000\", image:\"{{filepath:Application_icon.png}}\"},{ category:\"Application Component\",nodeColor:\"#00FF00\", image:\"{{filepath:Component_icon.png}}\"}]"
				),
			"edgeConfiguration" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.edgeConfiguration1").wfMessage("sprf.param.edgeConfiguration2"),
					"example" => "|edgeConfiguration=[{ relation:\"Belongs to application\",edgeColor:\"#00FF00\"}]"
				),
				"rootElement" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.rootElement"),
					"example" => ""
				),
				"rootElementColor" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.rootElementColor"),
					"example" => ""
				),
				"rootElementImage" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.rootElementImage"),
					"example" => ""
				),
				"showLegend" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.showLegend"),
					"example" => ""
				),
				"defaultNodeColor" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.defaultNodeColor"),
					"example" => "|defaultNodeColor=#CCC"
				),
				"defaultEdgeColor" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.defaultEdgeColor"),
					"example" => "|defaultEdgeColor=#CCC"
				),
				"splitQueryByUnion" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.splitQueryByUnion")
				),
				"minZoom" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.minZoom"),
					"example" => ""
				),
				"maxZoom" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.maxZoom"),
					"example" => ""
				),
				"layout" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.layout"),
					"default" => "dagre",
					"example" => ""
				),"layoutOptions" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.layoutOptions"),
					"example" => "|layoutOptions={rankSep:200}"
				),
				"maxWordLength" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.maxWordLength"),
					"example" => ""
				),"maxLabelLength" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.maxLabelLength"),
					"example" => ""
				),
				"nodeStyle" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.nodeStyle"),
					"example" => "|nodeStyle={shape:'roundrectangle', width:120, height:80,'text-valign': 'center','text-halign': 'center','font-size':'12','border-color' : '#000','border-width' : 1,'text-max-width':120}"
				),
				"edgeStyle" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.edgeStyle"),
					"example" => "|edgeStyle={'target-arrow-shape':''}",
					"example" => ""
				),
				"labelLinkPattern" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.labelLinkPattern"),
					"example" => ""
				),
				"categoryLinkPattern" => array(
					"mandatory" => false,
					"description" => wfMessage("sprf.param.categoryLinkPattern"),
					"example" => ""
				)
	   ); 
		$this->queryStructure = wfMessage("sprf.format.graph.query.structure").wfMessage("sprf.format.graph.query.structure.example")
		."<h3><b>".wfMessage("sprf.common.old.version")."</b></h3>"
		.wfMessage("sprf.format.graph.query.structure.old").wfMessage("sprf.format.graph.query.structure.old.example");	   
    }
	
	
	function generateHtmlContainerCode($options){
		$divId = $this->getParameterValue($options,'divId','');
		$divStyle =  $this->getParameterValue($options,'divStyle','');
		$divCssClass =  $this->getParameterValue($options,'divCssClass','');
		$escapedQuery = $this->getParameterValue($options,'sparqlEscapedQuery','');
		$htmlContainer = "<div id='$divId-container' style='$divStyle' class='$divCssClass ii-graph-container'>
			<div id='$divId' class='cytoscape-graph' style='width:100%; height:100%;' sparql-query='$escapedQuery'></div></div>";
		return $htmlContainer;
	}
	
	function generateJavascriptCode($options,$prefixes){
		$config = $this->generateConfig($options);
		$launch= $this->generateLaunchScript($options);
		$output = "$(document).ready(function(){
				$prefixes
				$config
				$launch
			});";
		return $output;	
	}
	
	
	function generateLaunchScript($options){
		$launchScript = "mw.loader.using( ['ext.SparqlResultFormat.main'], function () {
             mw.loader.using( 'ext.SparqlResultFormat.graph', function () {
                      spqlib.sparql2Graph(config);
              } );
        } );";
		return $launchScript;
	}
	
	
	function generateConfig($options){
		global $wgScriptPath;
		global $wgServer;
		$endpointIndex = $this->getParameterValue($options,'sparqlEndpoint','');
		$endpointData = $this->getSparqlEndpointByName($endpointIndex);
		$endpoint = $endpointData['url'];
		$basicAuthBase64String = $this->getSparqlEndpointBasicAuthString($endpointData);			
		$divId = $this->getParameterValue($options,'divId','');
		$divStyle = $this->getParameterValue($options,'divStyle','');
		$escapedQuery = $this->getParameterValue($options,'sparqlEscapedQuery','');
		$spinnerImagePath = $this->getParameterValue($options,'spinnerImagePath',"$wgScriptPath/extensions/SparqlResultFormat/img/spinner.gif");
		
		$divCssClass = $this->getParameterValue($options,'divCssClass',''); 
		$nodeConfiguration = $this->getParameterValue($options,'nodeConfiguration','{}');
		$edgeConfiguration = $this->getParameterValue($options,'edgeConfiguration','{}');
		$rootElement = $this->getParameterValue($options,'rootElement','');
		$rootElementColor = $this->getParameterValue($options,'rootElementColor','');
		$rootElementImage = $this->getParameterValue($options,'rootElementImage','');
		$showLegend = $this->getParameterValue($options,'showLegend','true');
		$defaultNodeColor = $this->getParameterValue($options,'defaultNodeColor','#CCC');
		$defaultEdgeColor = $this->getParameterValue($options,'defaultEdgeColor','#CCC');
		$splitQueryByUnion = $this->getParameterValue($options,'splitQueryByUnion','false');
		$minZoom = $this->getParameterValue($options,'minZoom',1);
		$maxZoom = $this->getParameterValue($options,'maxZoom',1);
		$layout = $this->getParameterValue($options,'layout','dagre');
		$maxWordLength = $this->getParameterValue($options,'maxWordLength',12);
		$maxLabelLength = $this->getParameterValue($options,'maxLabelLength',30);
		$layoutOptions = $this->getParameterValue($options,'layoutOptions','{}');
		$nodeStyle  = $this->getParameterValue($options,'nodeStyle','{}');
		$edgeStyle = $this->getParameterValue($options,'edgeStyle','{}');
		$labelLinkPattern = $this->getParameterValue($options,'labelLinkPattern',"$wgServer$wgScriptPath/index.php/{%s}");
		$categoryLinkPattern = $this->getParameterValue($options,'categoryLinkPattern',"$wgServer$wgScriptPath/index.php/Category:{%s}");
		
		
		/*$extraOption = $this->getParameterValue($options,'extraOption','');
		if (is_array($extraOption)){
			$extraOptionString = implode("||", $extraOption);	
		} else {
			$extraOptionString = '';
		}*/
		
		$config = "var config = {};
			config.divId = '$divId';
			config.endpoint='$endpoint';
			config.sparql=$('#$divId').attr('sparql-query');
			config.queryPrefixes=prefixes;
			config.basicAuthBase64String='$basicAuthBase64String';
			config.spinnerImagePath='$spinnerImagePath';
			config.divCssClass='$divCssClass';
			config.nodeConfiguration=$nodeConfiguration;
			config.edgeConfiguration=$edgeConfiguration;
			config.rootElement='$rootElement';
			config.rootElementColor='$rootElementColor';
			config.rootElementImage='$rootElementImage';
			config.showLegend='$showLegend';
			config.defaultNodeColor='$defaultNodeColor';
			config.defaultEdgeColor='$defaultEdgeColor';
			config.splitQueryByUnion='$splitQueryByUnion';
			config.minZoom=$minZoom;
			config.maxZoom=$maxZoom;
			config.layout='$layout';
			config.maxWordLength=$maxWordLength;
			config.maxLabelLength=$maxLabelLength;
			config.layoutOptions=$layoutOptions;
			config.nodeStyle=$nodeStyle;
			config.edgeStyle=$edgeStyle;
			config.labelLinkPattern='$labelLinkPattern';
			config.categoryLinkPattern='$categoryLinkPattern';";	
			
		return $config;
	}	
	
	
}
?>