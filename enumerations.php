<?php
namespace DPSFolioProducer;

$assetFormat = array('Auto', 'JPEG', 'PNG', 'PDF');
$jpegQuality = array('Minimum', 'Low', 'Medium', 'High', 'Maximum');
$folioIntent = array('LandscapeOnly', 'PortraitOnly', 'Both');
$protectedAccess = array('Closed', 'Open', 'Free');
$downloadPriority = array('Low', 'Medium', 'High');
$orientation = array('Landscape', 'Portrait', 'Both');
$smoothScrolling = array('Never', 'Landscape', 'Portrait', 'Always');
$viewer = array('web', '');

class FolioProducer {
    public function create_session() {
        return $this->fp->request('POST','sessions');
    }
}
