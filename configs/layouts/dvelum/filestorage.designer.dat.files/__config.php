<?php return array (
  'namespace' => 'appFilestorageClasses',
  'runnamespace' => 'appFilestorageRun',
  'files' => 
  array (
    0 => '/resources/dvelum-module-filestorage/js/FilestorageUploadWindow.js',
    1 => '/js/lib/ext_ux/AjaxFileUpload.js',
  ),
  'langs' => 
  array (
  ),
  'actionJs' => 'Ext.onReady(function(){ 
  // Init permissions
  app.application.on("projectLoaded",function(module){
    if(Ext.isEmpty(module) || module === \'Filestorage\'){
      appFilestorageRun.mainPanel.setPermissions(app.permissions.canEdit("Filestorage"), app.permissions.canDelete("Filestorage"));
    }
  });
});',
); 