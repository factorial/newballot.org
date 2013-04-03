function loadSwf(swf_src, swf_width, swf_height)
{
    var requiredMajorVersion = 9;
    var requiredMinorVersion = 0;
    var requiredRevision = 0;
    
    var swf_align = 'middle';
    var swf_id = 'flash_swf';
    var swf_quality = 'high';
    var swf_bgcolor = '#fff';
    var swf_name = 'flash_swf';
    var swf_allowScriptAccess = 'sameDomain';
    
    var alternateContent = '<div style="border: 1px solid #a6a11e; background-color: #fffb94; text-align: center;">';
    alternateContent += '<div style="font-size: 1.5em; color: #a63232; padding-bottom: .5em;">Attention</div>';
    alternateContent += '<div style="font-weight: bold;">';
    alternateContent += '<p>This content requires the Adobe Flash Player 9.x or higher.</p>';
    alternateContent += '<p><a href="http://www.adobe.com/go/getflash/">Get Flash</a></p>';
    alternateContent += '</div>';
    alternateContent += '</div>';

    var hasReqestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);

    if (hasReqestedVersion)
    {
        AC_FL_RunContent(
                    'src', swf_src,
                    'width', swf_width,
                    'height', swf_height,
                    'align', swf_align,
                    'id', swf_id,
                    'quality', swf_quality,
                    'bgcolor', swf_bgcolor,
                    'name', swf_name,
                    'allowScriptAccess', swf_allowScriptAccess,
                    'type', 'application/x-shockwave-flash',
                    'codebase', 'http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab',
                    'pluginspage', 'http://www.adobe.com/go/getflashplayer',
                    'wmode', 'transparent'
        );
    }
    else { document.write(alternateContent); }
}