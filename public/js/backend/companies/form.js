$(function () {
    // init: side menu for current page
    $('li#menu-companies').addClass('menu-open active');
    $('li#menu-companies').find('.treeview-menu').css('display', 'block');
    $('li#menu-companies').find('.treeview-menu').find('.add-companies a').addClass('sub-menu-active');

    $('#company-form').validationEngine('attach', {
        promptPosition : 'topLeft',
        scroll: false
    });

    // init: show tooltip on hover
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
    });
    

});
// We fetch the username and root and make them a global variable
 var username = document.getElementById("username_text").textContent;
 var root = window.location.href.split("/companies")[0];


//image preview
 function loadPreview(input, id) {
    upload_text = document.getElementById("upload_text");
    
 
    if (input.files && input.files[0]) {
        //We first check if the file has the extension of a picture
        if(!['image/gif', 'image/jpeg', 'image/png'].includes(input.files[0]['type'])){
        	upload_text.innerHTML = "画像を選んで下さい（jpg, jpeg, png）";
        	upload_text.style.color = "Tomato";
        	document.getElementById("preview_image").src="../../../img/no-image/no-image.jpg";
        // Then if the size is ok
        }else if (input.files[0].size>5*1024**2){
        	upload_text.style.color = "Tomato";
    		upload_text.innerHTML = "ファイルの容量は大き過ぎます（推奨サイズ：1280 x 720；5MBまで）";
    		document.getElementById("preview_image").src="../../../img/no-image/no-image.jpg";
        }else {
        	//if everything is ok, we do the upload
		var reader = new FileReader();
		upload_text.style.color = "green";
	 	upload_text.innerHTML = "アップロードしました";
		reader.onload = function (e) {
		    $("#preview_image")
		            .attr('src', e.target.result)
		            // Used to conserve the picture if the page is reloaded
		            sessionStorage.setItem("previewImageData", e.target.result);

		};
		reader.readAsDataURL(input.files[0]);
	}
    }
 }
 // Used to reload the picture on page reload or submit
 window.onload = function() {
    // boolean variable telling us if the page has been reloaded or not
    const pageAccessedByReload = (
    (window.performance.navigation && window.performance.navigation.type === 1) ||
    window.performance
      .getEntriesByType('navigation')
      .map((nav) => nav.type)
      .includes('reload')
    );
    // Useful variables
    upload_text = document.getElementById("upload_text");
    previewImage = sessionStorage.getItem("previewImageData");
    
    if (document.getElementById("image").value){
	upload_text.style.color = "green";
 	upload_text.innerHTML = "アップロードしました";    
        document.getElementById("preview_image").src = previewImage;
    }
    else if(!pageAccessedByReload){
	$.get(root+"/api/companies/tempPicture/"+username,function(data){
		if(data == "keep"){
			// Load the the preview picture
			upload_text.style.color = "green";
		 	upload_text.innerHTML = "アップロードしました";    
			document.getElementById("preview_image").src = previewImage;
		}
	})
	.fail(function(error) {
        	console.log(error);
	});
    }
    // We check if there is a image in temp to display through a request
    else if(pageAccessedByReload){
        $.get(root+"/api/companies/tempRefresh/"+username,function(data){
		if(data == "display"){
			//Fill the text input with the result of the search
			upload_text.style.color = "green";
		 	upload_text.innerHTML = "アップロードしました";    
			document.getElementById("preview_image").src = previewImage;
		}
	})
	.fail(function(error) {
        	console.log(error);
	});
    }
    

}

 	
 
 //Postcode search script
 function fillLocation(){
 	postcode = document.getElementById("postcode").value.replace(/\D/g,''); //The character '-' is removed if it was written
 	document.getElementById("postcode").value = postcode

 	
 	// Give the value 0 to postcode if it is empty. It prevents the request from crashing.
 	if(postcode.length == 0){postcode = 0;}

	// We create our URL for the request 
 	const urlReq = root+"/api/postcodes/"+postcode;


	$.get(urlReq,function(data){
		// Checking the prefecture information is enough to know that all the information was collected
		if(data.prefecture !== null){
			//Fill the text input with the result of the search
			document.getElementById("prefecture").value = data.prefecture;
			document.getElementById("city").value = data.city;
			document.getElementById("local").value = data.local;
		}else{
			document.getElementById("postcode_text").textContent = "検索出来ませんでした";
		}		
	})
	.fail(function(error) {
        	console.log(error);
	});
		
 }
 //Erase the message for the search failure if the user corrected it
 function eraseMessage(){document.getElementById("postcode_text").textContent = "";}
 
 