(function () {

    $(".deleteProduct").on("submit", function(){
        return confirm("Voulez-vous supprimer ce produit ?");
    });

    $(".deleteCategory").on("submit", function(){
        return confirm("Voulez-vous supprimer cette gatégorie ?");
    });

})($)
