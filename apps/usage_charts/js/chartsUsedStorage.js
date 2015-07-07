/**
* ownCloud - Usage Charts plugin Forked from DjazzLab Storage Charts Plugin
* @author Alan Vallance
// +-----------------------------------------------------------------------+
// | Copyright (C) 2013, ed-237                                            |
// +-----------------------------------------------------------------------+
// | This file is free software; you can redistribute it and/or modify     |
// | it under the terms of the CeCIll v2 as published by three French      |
// | public research organisations, the CEA, the CNRS and INRIA            |
// | This file is distributed in the hope that it will be useful           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of        |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the          |
// | CeCIll License for more details.                                      |
// | http://www.cecill.info/licences/Licence_CeCILL_V2-en.txt              |
// +-----------------------------------------------------------------------+
// | Author: pFa                                                           |
// +-----------------------------------------------------------------------+
*/
var OC_DLSC={};
//		OC_DLSC.ygt as Yearly graph title
//		OC_DLSC.ygs as Yearly graph series
//		OC_DLSC.ygu as Yearly graph unit
//
//		OC_DLSC.wgst as Weekly graph subtitle
//		OC_DLSC.wgt  as Weekly graph title
//		OC_DLSC.wgs  as Weekly graph series
//		OC_DLSC.wgu  as Weekly graph unit
//
//		OC_DLSC.dgs  as Daily  graph series
//
Array.prototype.rotate = ( function () {
//              ------
	var	myUnshift=Array.prototype.unshift,
		mySplice=Array.prototype.splice;
	return function( theCount ) {
		var	myLen	= this.length >>> 0,
			myCount	= theCount >> 0;
		myUnshift.apply(this, mySplice.call(this, myCount%myLen, myLen) );
		return this;
		};
	})();
//
Date.prototype.addDays = function ( theNumberOfDays ) {
//             -------
// theNumberOfDays may be a positive or a negative number
	this.setDate(this.getDate() + theNumberOfDays);
	return this;
}
//

function monthlyUsedStorage() {
//       ------------------
var	myD=new Date(),
	myCM=myD.getMonth(),	// January = 0
	myCY=myD.getFullYear() - 1,
	i=0;
// Init OC_DLSC.m from an existing array of translated month names
OC_DLSC.m=[]; for(;i<12;i++) OC_DLSC.m[i]=monthNames[i];
// Rotate the months depending on current month, then append the right year to each month
i = 0;
if ( myCM < 11 ) {
	OC_DLSC.m.rotate(myCM+1);
	for ( ; i < 11 - myCM ; i++ )
			OC_DLSC.m[i] += ' '+myCY;
}
++myCY;
for ( ; i < 12 ; i++ )	OC_DLSC.m[i] += ' '+myCY;
// Create the graph
var histous=new Highcharts.Chart(
	{chart:{ renderTo:		'histo_us'
		,backgroundColor:	'#F8F8F8'
		,plotBackgroundColor:	'#F8F8F8'
		,type:			'column'
		}
	,title:{ text:''}
	,xAxis:{ categories:OC_DLSC.m}
	,yAxis:{ min:0
		,title:		{ text:	OC_DLSC.ygt}
		,stackLabels:	{enabled:true
				,style:{ fontWeight:'bold'
					,color:(Highcharts.theme&&Highcharts.theme.textColor)||'gray'
					}
				,formatter:function(){
						return Math.round(this.total*100)/100;
						}
				}
		}
	,legend:{align:'center'
		,x:-20
		,verticalAlign:'top'
		,y:20
		,floating:true
		,backgroundColor:(Highcharts.theme&&Highcharts.theme.legendBackgroundColorSolid)||'white'
		,borderColor:'#CCC'
		,borderWidth:1
		,shadow:false
		}
	,tooltip:{formatter:function(){
				return '<b>'+this.x+'</b><br/>'+this.series.name+': '+(Math.round(this.y*100)/100)+' '+OC_DLSC.ygu+' <br/>Total: '+(Math.round(this.point.stackTotal*100)/100)+' '+OC_DLSC.ygu;
				}
		}
	,plotOptions:{	column:{ stacking:'normal'
				,dataLabels:{	 enabled:false
						,color:(Highcharts.theme&&Highcharts.theme.dataLabelsColor)||'white'
						}
				}
		}
	,series:OC_DLSC.ygs
	,exporting:{enabled:false}
	});
} // monthlyUsedStorage

function weeklyUsedStorage() {
//       -----------------

var	myD=new Date(),
	myCM=myD.getMonth(),	// January = 0
	myDW=myD.getDay(),	// Sunday = 0
	myDM=myD.getDate(),	// 1 - 31
	i=0;
// Init OC_DLSC.m from an existing array of translated day names
OC_DLSC.d=[]; for(;i<7;i++) OC_DLSC.d[i]=dayNames[i];
// Rotate the days of week depending on current day, then append the right day of month to each day of week
i = 0;
if ( myDW < 6 )	OC_DLSC.d.rotate(myDW+1);
myD.addDays( -6 );
for ( ; i < 7 ; i++ ) {
	OC_DLSC.d[i] += ' '+myD.getDate();
	myD.addDays(1);
}

var linesusse=new Highcharts.Chart(
	{chart:{ renderTo:		'lines_usse'
		,backgroundColor:	'#F8F8F8'
		,plotBackgroundColor:	'#F8F8F8'
		,type:			'line'
		}
	,title:{ text:''}
	,subtitle:{
		 text:		OC_DLSC.wgst
		,x:-20
		}
	,xAxis:{ categories:	OC_DLSC.d}
	,yAxis:{ title:	{text:	OC_DLSC.wgt}
		,plotLines:[{value:0,width:1,color:'#808080'}]
		,startOnTick:false
		,min:0
		}
	,tooltip:{
		 crosshairs:true,
		 formatter:function () {
		 	return '<b>'+this.series.name+'</b><br/>'+this.x+': '+this.y+' '+OC_DLSC.wgu;
			}
		}
	,legend:{layout:	'horizontal'
		,align:		'center'
		,verticalAlign:	'top'
		,x:		-25
		,y:		40
		,borderWidth:	0
		}
	,series:		OC_DLSC.wgs
	,exporting:{enabled:false}
	});
} // weeklyUsedStorage

function pieUsedStorage() {
//       --------------

var pierfsus=new Highcharts.Chart(
	{chart:{ renderTo:		'pie_rfsus'
		,backgroundColor:	'#F8F8F8'
		,plotBackgroundColor:	'#F8F8F8'
		,plotBorderWidth:	false
		,plotShadow:false
		}
	,title:{ text:''}
	,tooltip:{formatter:function (){
				return '<b>'+this.point.name+'</b>: '+(Math.round(this.percentage*100)/100)+' %';
				}
		}
	,plotOptions:{
		 pie:{	 allowPointSelect:true
		 	,cursor:'pointer'
			,dataLabels:{
				 enabled:true
				,color:'#000000'
				,connectorColor:'#000000'
				,formatter:function () {
					return '<b>'+this.point.name+'</b>: '+Math.round(this.percentage)+' %';
					}
				}
			}
		}
	,series:[
		{type:	'pie'
		,name:	'Used-Free space ratio'
		,data:	OC_DLSC.dgs
		,exporting:{enabled:false}
		}]
	});
} // pieUsedStorage

function changeGraphUnit( theLoaderID, theGraphID, theValue ) {
//       ---------------
		theLoaderID.html('<img src="'+OC.imagePath('usage_charts','loader.gif')+'" />');
		$.ajax(
			{type:'POST'
			,url:OC.linkTo('usage_charts','ajax/chartsData.php')
			,dataType:'json'
			,data:{k:theGraphID,u:theValue}
			,async:true
			,success:function(s){
				eval(s.r);
				theLoaderID.find('img').remove();
				}
			});
} // changeGraphUnit

$(document).ready(function(){
	$('#stc_sortable').sortable({
		 axis:'y'
		,handle:'h3'
		,placeholder:'ui-state-highlight'
		,update:function(e,u){
			$.ajax({
//			Send the new order of the graphs to ownCloud user prefs
				 type:'POST'
				,url:OC.linkTo('usage_charts','ajax/config.php')
				,dataType:'json'
				,data:{o:'set',k:'sc_sort',i:$('#stc_sortable').sortable('toArray')}
				,async:true
			});
		}
	});
	$('#stc_sortable').disableSelection();
//
	var myGraphs	= $('#stc_sortable').sortable('toArray'),
	    i;
//
	for ( i=0; i < myGraphs.length; i++ ) {
//	Use ajax to retrieve data for each enabled chart
		$.ajax({
			 type:'POST'
			,url:OC.linkTo('usage_charts','ajax/chartsData.php')
			,dataType:'json'
			,data:{k:myGraphs[i]}
			,async:true
			,success:function(s){
				if ( s ) {
					eval(s.r);
				}
			}
		});
	}
	$('#chunits')
		.chosen()
		.change(function () {
			changeGraphUnit($('#selLoader'),'clines_usse', this.value);
		});
	$('#chunits_hus')
		.chosen()
		.change(function () {
			changeGraphUnit($('#selLoader_hus'),'chisto_us', this.value);
		});
	$('td.settings')
		.on('click keydown',function () {
//		End-user clicked the "Settings" icon
//		Launch a pop up containing the settings (app/settings.php)
//		and its loadJS (app/js/settings.js)
			OC.appSettings({appid:'usage_charts',loadJS:true});
		});
//
});
