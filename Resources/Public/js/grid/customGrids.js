jQuery(document).ready(function() {
	  mygrid = jQuery("#searchjqgrid").jqGrid({
		url:leaveListGrid,
		datatype: "json",
		colNames:[employee,reason,fromDate,toDate,hasTeamLeaderApproved,hasDirectorApproved],
		colModel:[
			{name:'employee',index:'employee',sortable:true,width:'120',search:true},
			{name:'reason',index:'reason',sortable:true,width:'120',search:false},
			{name:'fromDate',index:'fromDate', width:90, searchoptions:{dataInit: function (elem) {
				$(elem).datepicker({
					changeYear: true,
					changeMonth: true,
					showButtonPanel: true,
					dateFormat: "yy-m-d",
					onSelect: function() {
						mygrid[0].triggerToolbar();
					}
				});
			}}},
			{name:'toDate',index:'toDate', width:90, searchoptions:{dataInit: function (elem) {
				$(elem).datepicker({
					changeYear: true,
					changeMonth: true,
					showButtonPanel: true,
					dateFormat: "yy-m-d",
					onSelect: function() {
						mygrid[0].triggerToolbar();
					}
				});
			}}},
				{name:'hasDirectorApproved',index:'hasDirectorApproved',sortable:true,width:'100',search:false},
				{name:'hasTeamLeaderApproved',index:'hasTeamLeaderApproved',sortable:true,width:'90',search:false}
		],
		rowNum:10,
		rowList:[10,50,100,150],
		pager: '#paginationdiv',
		//multiselect: true,
		mtype: "GET",
		sortname: 'reason',
		height:'auto',
		width: '900',
		rownumbers:true,
		viewrecords: true,
		sortorder: 'asc',
		gridview: true,
		caption: search_caption,
		autowidth: false
	});

	jQuery("#searchjqgrid").jqGrid('navGrid','#paginationdiv',{edit:false,add:false,del:false,search:true,refresh:false});
	jQuery("#searchjqgrid").jqGrid('navButtonAdd',"#paginationdiv",{caption:"Toggle",title:"Toggle Search Toolbar", buttonicon :'ui-icon-pin-s',
		onClickButton:function(){
			mygrid[0].toggleToolbar()
		}
	});
	jQuery("#searchjqgrid").jqGrid('navButtonAdd',"#paginationdiv",{caption:"Clear",title:"Clear Search",buttonicon :'ui-icon-refresh',
		onClickButton:function(){
			mygrid[0].clearToolbar()
		}
	});

    mygrid.filterToolbar({autosearch: true, searchOnEnter: false});
});
