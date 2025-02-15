Ext.BLANK_IMAGE_URL = '/extjs/resources/images/default/s.gif';

Ext.onReady(function(){
    Ext.QuickTips.init();

    // Create the "SampleTreePanel" pre-configured class

    SampleTreePanel = Ext.extend(Ext.tree.TreePanel, {
        title: 'Sample Tree Panel',
        width: 200,
        height: 200,
        loader: new Ext.tree.TreeLoader(),
        rootVisible: false,
        border: false,

        initComponent: function(){
            Ext.apply(this, {

                root: new Ext.tree.AsyncTreeNode({
                    children: [{
                        text: 'First',
                        expanded: true,
                        children: [{
                            text: 'one',
                            leaf: true
                        }, {
                            text: 'two',
                            leaf: true
                        }]
                    }, {
                        text: 'Second',
                        expanded: true,
                        children: [{
                            text: 'one',
                            leaf: true
                        }]
                    }]
                })
            })

            SampleTreePanel.superclass.initComponent.apply(this, arguments);
        }
    });
    Ext.reg('tree_panel', SampleTreePanel);
    // Instantiate the tree panel, then attach an event listener..

    var tree = new SampleTreePanel();

    tree.on('click', function(node, e){
        debugger;
    }, this);

    // And create a window to display the tree panel in...

    var wind = new Ext.Window({
        plain: true,
        bodyStyle: 'padding:5px;',
        layout: 'fit',
        items: [tree]
    });

    wind.show();
});
