$(document).ready(function(e) {
    // Ouvrir modal confirm supprimer produit
    $(document).on('click', '.del-product', (e) => {
        let href = $(e.target).data('href')
        $('.link-del-produit').attr('href', href)
    })

    // Ouvrir modal stock
    $(document).on('click', '.stock-product', (e) => {
        let href  = $(e.target).data('href')
        let stock = $(e.target).data('stock')

        $('#stock-input').val(stock)

        $('#form-stock').attr('action', href)
    })

    // Incrémenter la valeur
    $("#button-increment-q").click(function(){
        let stock = $('#stock-input')
        var currentValue = parseInt(stock.val());
        stock.val(++currentValue);
    });

    // Décrémenter la valeur
    $("#button-decrement-q").click(function(){
        let stock = $('#stock-input')
        var currentValue = parseInt(stock.val());
        if(currentValue > 0){
            stock.val(--currentValue);
        }
    });
})