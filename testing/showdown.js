$(document).ready(function(){

    var target = $('#targetDiv');

    var converter = new showdown.Converter({
        tables: true,
        tasklists: true
    });

    $('#btn_convert').click(function(){
        var text = $('#sourceTA').val();
        var html = converter.makeHtml(text);
        target.html(html);
    });

    $('#btn_ajax').click(function(){
        var html = $.ajax({
            url: 'showdown.md',
            success: function(data){
                var html = converter.makeHtml(data);
                target.html(html);
            }
        });
    });

});
