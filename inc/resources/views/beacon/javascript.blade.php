/**
 * Beacon JavaScript
 *
 * @package DuckSell
 * @author Milos Stojanovic <info@interactive32.com>
 * @copyright Copyright 2008-2016 Interactive32.com
 */

(function () {

    /*
     * Get page referrer
     */
    function getReferrer() {
        var referrer = '';

        try {
            referrer = windows.top.document.referrer;
        } catch (e) {
            if (window.parent) {
                try {
                    referrer = window.parent.document.referrer;
                } catch (e2) {
                    referrer = '';
                }
            }
        }

        if (referrer === '') {
            referrer = document.referrer;
        }

        if (typeof referrer == 'undefined') {
            referrer = '';
        }

        return referrer;
    }

    var conf = [];
    var image = new Image(1, 1);
    var referrer = getReferrer();
    var i;

    // write all params to config
    for (i = 0; i < _daq.length; i++) {
        if (_daq[i]) {
            conf[_daq[i][0]] = _daq[i][1];
        }
    }

    image.src = '{{ $url }}?site_id='+conf['site_id']+'&r='+encodeURIComponent(referrer);

})();
