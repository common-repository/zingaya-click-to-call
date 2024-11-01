function get_sliders() {
    jQuery(".work_hours").slider({
        from: 0,
        to: 1440,
        step: 60,
        dimension: '',
        scale: ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00', '24:00'],
        limits: false,
        calculate: function( value ){
            var hours = Math.floor( value / 60 );
            var mins = ( value - hours*60 );
            return (hours < 10 ? "0"+hours : hours) + ":" + ( mins == 0 ? "00" : mins );
        },
        onstatechange: function( value ){
            //console.dir( this );
        }
    });
}