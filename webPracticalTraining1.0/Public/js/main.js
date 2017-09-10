/*-----------------------------------------------------------------------------------*/
/* 		Mian Js Start 
/*-----------------------------------------------------------------------------------*/
$(document).ready(function($) {
"use strict"
/*-----------------------------------------------------------------------------------*/
/*		STICKY NAVIGATION
/*-----------------------------------------------------------------------------------*/
$(".sticky").sticky({topSpacing:0});
/*-----------------------------------------------------------------------------------*/
/* 	LOADER
/*-----------------------------------------------------------------------------------*/
$("#loader").delay(500).fadeOut("slow");

/*-----------------------------------------------------------------------------------*/
/* 	SERVICE SELECT
/*-----------------------------------------------------------------------------------*/
$('.choose-ser li').on('click', function () {
    $('.choose-ser li').removeClass('active');
    $(this).addClass('active');
return false;
});
/*-----------------------------------------------------------------------------------*/
/* 	GALLERY SLIDER
/*-----------------------------------------------------------------------------------*/
$('.block-slide').owlCarousel({
    loop:true,
    margin:30,
    nav:true,
	navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:4
        }
}});
/*-----------------------------------------------------------------------------------*/
/* 	TESTIMONIAL SLIDER
/*-----------------------------------------------------------------------------------*/
$(".single-slide").owlCarousel({ 
    items : 1,
	autoplay:true,
	loop:true,
	autoplayTimeout:5000,
	autoplayHoverPause:true,
	singleItem	: true,
    navigation : true,
	navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
	pagination : true,
	animateOut: 'fadeOut'	
});
$('.item-slide').owlCarousel({
    loop:true,
    margin:30,
    nav:false,
	navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
    responsive:{
        0:{
            items:1
        },
        400:{
            items:2
        },
		900:{
            items:3
        },
        1200:{
            items:4
        }
    }
});

});
/*-----------------------------------------------------------------------------------*/
/*    CONTACT FORM
/*-----------------------------------------------------------------------------------*/
function checkmail(input){
  var pattern1=/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
  	if(pattern1.test(input)){ return true; }else{ return false; }}     
    function proceed(){
    	var name = document.getElementById("name");
		var email = document.getElementById("email");
		var company = document.getElementById("company");
		var web = document.getElementById("website");
		var msg = document.getElementById("message");
		var errors = "";
		if(name.value == ""){ 
		name.className = 'error';
	  	  return false;}    
		  else if(email.value == ""){
		  email.className = 'error';
		  return false;}
		    else if(checkmail(email.value)==false){
		        alert('Please provide a valid email address.');
		        return false;}
		    else if(company.value == ""){
		        company.className = 'error';
		        return false;}
		   else if(web.value == ""){
		        web.className = 'error';
		        return false;}
		   else if(msg.value == ""){
		        msg.className = 'error';
		        return false;}
		   else 
		  {
	$.ajax({
		type: "POST",
		url: "php/submit.php",
		data: $("#contact_form").serialize(),
		success: function(msg){
		//alert(msg);
		if(msg){
			$('#contact_form').fadeOut(1000);
			$('#contact_message').fadeIn(1000);
				document.getElementById("contact_message");
			 return true;
		}}
	});
}};


