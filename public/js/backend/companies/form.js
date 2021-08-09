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

		};
	 
		reader.readAsDataURL(input.files[0]);
	}
    }
 }
 
 //Postcode search script
 function fillLocation(){
 	postcode = document.getElementById("postcode").value.replace(/\D/g,''); //The character '-' is removed if it was written

	// We create our URL for the request 
 	const urlReq = window.location.origin+"/api/postcodes/"+postcode;

	fetch(urlReq, {
        method: 'GET',
        mode:'no-cors',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            }
        })
	.then(resp=>resp.json())
	.then(function(data){
		console.log(data);
		// Checking the prefecture information is enough to know that all the information was collected
		if(data.prefecture){
			//Fill the text input with the result of the search
			document.getElementById("prefecture").value = data.prefecture;
			document.getElementById("city").value = data.city;
			document.getElementById("local").value = data.local;
		}else{
			document.getElementById("postcode_text").textContent = "検索出来ませんでした";
		}		
	})
	.catch(function(error) {
        	console.log(error);
	});
		
 }
 //Erase the message for the search failure if the user corrected it
 function eraseMessage(){document.getElementById("postcode_text").textContent = "";}
 
 