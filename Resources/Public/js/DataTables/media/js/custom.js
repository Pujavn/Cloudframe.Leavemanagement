var editor; // use a global for the submit and return data rendering in the examples
 
$(document).ready(function() {
    editor = new $.fn.dataTable.Editor( {
        "ajaxUrl": "leaveListGrid",
        "domTable": "#example",
        "fields": [ {
                "label": "Browser:",
                "name": "browser"
            }, {
                "label": "Rendering engine:",
                "name": "engine"
            }, {
                "label": "Platform:",
                "name": "platform"
            }, {
                "label": "Version:",
                "name": "version"
            }, {
                "label": "CSS grade:",
                "name": "grade"
            }
        ]
    } );
 
    $('#example').dataTable( {
        "sDom": "Tfrtip",
        "sAjaxSource": "php/browsers.php",
        "aoColumns": [
            { "mData": "browser" },
            { "mData": "engine" },
            { "mData": "platform" },
            { "mData": "version", "sClass": "center" },
            { "mData": "grade", "sClass": "center" }
        ],
        "oTableTools": {
            "sRowSelect": "multi",
            "aButtons": [
                { "sExtends": "editor_create", "editor": editor },
                { "sExtends": "editor_edit",   "editor": editor },
                { "sExtends": "editor_remove", "editor": editor }
            ]
        }
    } );
} );