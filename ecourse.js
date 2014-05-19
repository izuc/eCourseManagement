Ext.require([
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.util.*',
    'Ext.state.*',
    'Ext.form.*'
]);

Ext.onReady(function(){

    Ext.define('Course', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'course_id', type: 'int'},
            'course_title',
			'course_description',
            { name: 'start_date', type: 'date', dateFormat: 'd-m-Y' },
			{ name: 'end_date', type: 'date', dateFormat: 'd-m-Y' },
            { name: 'price', type: 'float' }
        ]
    });

	
    var store = Ext.create('Ext.data.Store', {
		autoLoad: true,
        model: 'Course',
        proxy: {
            type: 'ajax',
			url: 'system.php',
			extraParams: {
				action: 2
			},
			reader: {
                type: "json"
            }
        }
    });

    var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
        clicksToMoveEditor: 1,
        autoCancel: false,
		listeners: {
			edit: function(editor,e,opt) {
				store.getProxy().setExtraParam('action', 3);
				store.sync();
			},
			afteredit: function(editor,e,opt) {
				// After a one second delay, it will reload the data.
				var myAutoReload = new Ext.util.DelayedTask(function() {
					store.getProxy().setExtraParam('action', 2);
					store.load();
				});
				myAutoReload.delay(1000);
			}
		}
    });

    var grid = Ext.create('Ext.grid.Panel', {
        store: store,
        columns: [{
			header: "Course ID", 
			dataIndex: 'course_id',  
			hidden: true
		}, {
            header: 'Course Title',
            dataIndex: 'course_title',
            flex: 1,
            editor: {
                allowBlank: false
            }
        }, {
            header: 'Description',
            dataIndex: 'course_description',
            width: 160,
            editor: {
                allowBlank: false
            }
        }, {
            xtype: 'datecolumn',
            header: 'Start Date',
            dataIndex: 'start_date',
            width: 105,
			renderer: Ext.util.Format.dateRenderer('d-m-Y'),
            editor: {
                xtype: 'datefield',
                allowBlank: false,
                format: 'd-m-Y'
            }
        }, {
            xtype: 'datecolumn',
            header: 'End Date',
            dataIndex: 'end_date',
            width: 105,
			renderer: Ext.util.Format.dateRenderer('d-m-Y'),
            editor: {
                xtype: 'datefield',
                allowBlank: false,
                format: 'd-m-Y'
            }
        }, {
            xtype: 'numbercolumn',
            header: 'Cost',
            dataIndex: 'price',
            format: '$0,0',
            width: 90,
            editor: {
                xtype: 'numberfield',
                allowBlank: false,
                minValue: 1,
                maxValue: 5000
            }
        }],
        renderTo: 'course-grid',
        width: 600,
        height: 400,
        title: 'Online Courses',
        frame: true,
        tbar: [{
            text: 'Add Course',
            handler : function() {
                rowEditing.cancelEdit();
                var r = Ext.create('Course', {
                    course_title: 'New Course',
					course_description: 'Description here',
                    start_date: Ext.Date.clearTime(new Date()),
					end_date: Ext.Date.clearTime(new Date()),
                    price: 1000
                });
                store.insert(0, r);
                rowEditing.startEdit(0, 0);
            }
        }, {
            itemId: 'removeCourse',
            text: 'Remove Course',
            handler: function() {
                var sm = grid.getSelectionModel();
                rowEditing.cancelEdit();
				store.getProxy().setExtraParam('action', 4); // delete
                store.remove(sm.getSelection());
				store.sync();
                if (store.getCount() > 0) {
                    sm.select(0);
                }
				var myAutoReload = new Ext.util.DelayedTask(function() {
					store.getProxy().setExtraParam('action', 2);
					store.load();
				});
				myAutoReload.delay(1000);
            },
            disabled: true
        }],
        plugins: [rowEditing],
        listeners: {
            'selectionchange': function(view, records) {
                grid.down('#removeCourse').setDisabled(!records.length);
            }
        }
    });
});
