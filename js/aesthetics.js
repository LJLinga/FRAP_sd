/**
 * Created by Serus Caligo on 11/3/2018.
 */

$(document).ready( function(){
    // View Document and Add and Edit Document
    // load hidden
    $("#docRef1").hide();
    $("#docRef2").hide();
    $("#docRef3").hide();

    $("#docRef1B").click(function () {
        $("#docRef1").toggle(100);
    });

    $("#docRef2B").click(function () {
        $("#docRef2").toggle(100);
    });

    $("#docRef3B").click(function () {
        $("#docRef3").toggle(100);
    });
    //-- End
});
