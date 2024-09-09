jQuery(function ($) {
    $('.req_prod[data-id=45]').addClass('active');
    // $('#filter').submit(function () {
    //     var filter = $(this);
    //     // alert('Если это работает, уже неплохо'); // выводим сообщение
    //     $.ajax({
    //         // url: true_obj.ajaxurl, // обработчик
    //         url: 'https://eurokallur.ee/wp-admin/admin-ajax.php', // обработчик
    //         data: filter.serialize(), // данные
    //         type: 'POST', // тип запроса
    //         beforeSend: function (xhr) {
    //             filter.find('button').text('Загружаю...'); // изменяем текст кнопки
    //         },
    //         success: function (data) {
    //             filter.find('button').text('Применить фильтр'); // возвращаеи текст кнопки
    //             $('#response').html(data);
    //             console.log(data);
    //         }
    //     });
    //     return false;
    // });

    const categoriesMenu = $('.req_prod');
    categoriesMenu.on('click', function () {
        let categoryId = $(this).attr('data-id');
        $('.req_prod').removeClass('active');
        $(this).addClass('active');
        $.ajax({
            // url: true_obj.ajaxurl, // обработчик
            url: 'https://eurokallur.ee/wp-admin/admin-ajax.php', // обработчик
            data: {
                action: 'myfilter',
                categoryfilter: categoryId
            }, // данные
            type: 'POST', // тип запроса
            beforeSend: function (xhr) {

            },
            success: function (data) {
                $('#response').html(data);
                // console.log(data);
            }
        });
        return false;
    });

    

    // $('.req_prod').click(function () {
    //     // var esa_idcat = $(this).attr('data-id');
    //     var esa_idcat = $(this);
    //     // console.log(this);
    //     // console.log(esa_idcat);
    //     $.ajax({
    //         // url: true_obj.ajaxurl, // обработчик
    //         url: 'https://eurokallur.ee/wp-admin/admin-ajax.php', // обработчик
    //         data: esa_idcat.serialize(), // данные
    //         type: 'POST', // тип запроса
    //         beforeSend: function (xhr) {
    //             // filter.find('button').text('Загружаю...'); // изменяем текст кнопки
    //         },
    //         success: function (data) {
    //             // filter.find('button').text('Применить фильтр'); // возвращаеи текст кнопки
    //             $('#response').html(data);
    //         }
    //     });
    //     return false;
    // })

});