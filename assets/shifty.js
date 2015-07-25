// Плагин для мультиселекта строк
(function ($) {
    $.fn.shifty = function (o) {
        var o = $.extend({
            className: 'shifty-select', // название класса по умолчанию
            select: function () {
            },  // функция при выделении
            unselect: function () {
            } // функция при снятии выделения
        }, o);
        elems = $(this); // получаем элементы
        last = null;
        var className = o.className; // и название класса
        return $(this).each(function () {
            var block = $(this); // работаем с отдельным элементом
            $(document).keydown(function (e) { // при нажатии клавиши
                if (!e.ctrlKey && !e.shiftKey) return;  // если клавиша не shift и не ctrl выходим из функции
                this.onselectstart = function () {
                    return false
                }; // запрет выделения для IE
                block.unbind('click').css({
                    '-moz-user-select': 'none',
                    '-webkit-user-select': 'none',
                    'user-select': 'none'
                }); // и для всех остальных браузеров
                if (e.ctrlKey) {  // если нажата клавиша ctrl
                    block.click(function () {
                        block.toggleClass(className); // снимаем либо добавляем выделение
                        last = elems.index(block); // определяем номер элемента
                        o.unselect(elems); // снимаем выделение, выполняя пользовательскую функцию
                        o.select(elems.filter('.' + className)); // добавляем пользовательскую функцию для выделенных элементов
                    });
                }
                if (e.shiftKey) { // если нажата клавиша shift
                    block.click(function () {
                        first = elems.index(block); // находим элемент, с которого начнётся выделение
                        if (first < last) { // выделяем последующие элементы в зависимости от направления
                            elems.filter(':gt(' + (first - 1) + ')').addClass(className);
                            elems.filter(':lt(' + first + '),:gt(' + last + ')').removeClass(className);
                        } else {
                            elems.filter(':gt(' + last + ')').addClass(className);
                            elems.filter(':lt(' + last + '),:gt(' + first + ')').removeClass(className);
                        }
                        o.unselect(elems);  // снимаем выделение пользовательской функцией
                        o.select(elems.filter('.' + className)); // добавляем пользовательскую функцию для элемента
                    });
                }
            });
            $(document).keyup(function (e) {  // когда клавиша отпущена
                this.onselectstart = function () {
                }; // снимаем запрет выделения с IE
                block.unbind('click').click(blockClick).css({
                    '-moz-user-select': '',
                    '-webkit-user-select': '',
                    'user-select': ''
                }); // и с остальных браузеров
            });
            block.click(blockClick); // устанавливаем обработчик клика
        });
        function blockClick() { // обработчик простого клика
            elems.removeClass(className); // снимаем выделение со всех элементов
            $(this).addClass(className); // добавляем выделение к текущему элементу
            o.unselect(elems); // то же самое с пользовательской функцией
            o.select($(this));
            last = elems.index($(this));
        }
    };
})(jQuery);