(function() {
	function $id(id) {
		return document.getElementById(id);
	}

	function output(msg) {
		var m = $id("response");
		
		m.innerHTML = msg + m.innerHTML;
	}

	function fileDragHover(e) {
		e.stopPropagation();
		e.preventDefault();
		e.target.className = (e.type == "dragover" ? "hover" : "");
	}

	function fileSelectHandler(e) {
		fileDragHover(e);

		var files = e.target.files || e.dataTransfer.files;

		for(var i = 0, f; f = files[i]; i++) {
			parseFile(f);
			uploadFile(f);
		}
	}

	function getPath(filename) {
		var parts = filename.split(".");
		var extension = parts.pop();
	}

	function parseFile(file) {		
		var reader = new FileReader();

		var path = getPath(file.name);

		reader.onload = function(e) {
			console.log(file);
			if(file.type.indexOf("image") == 0) {
				var preview = '<a href="' + e.target.result + '" class="no-border" target="_blank"><img style="width: 220px; height: 220px; padding: 2px; border: 1px solid #00B4FF;" src="' + e.target.result + '" /></a>';
			} else {
				if(file.type == "audio/mp3") {
					var preview = '<audio controls style="width: 220px;">';
						preview = preview + '<source src="' + e.target.result + '" type="audio/mpeg">';
  						preview = preview + '</audio>';
				} else if(file.type == "video/mp4") {
					var preview = '<video width="220" height="130" controls>';
  						preview = preview + '<source src="' + e.target.result + '" type="video/mp4">';
						preview = preview + '</video>';
				} else {
					var preview = '';
				}
			}
			
			output(
				'<div class="span3" style="margin-bottom: 10px; text-align: left;">' +
					preview +
					'<br /><strong>' + file.name + '</strong>' +
					'<input name="files[]" type="hidden" value="' + e.target.result + '">' +
					'<input name="names[]" type="hidden" value="' + file.name + '">' +
					'<input name="types[]" type="hidden" value="' + file.type + '">' +
					'<input name="sizes[]" type="hidden" value="' + file.size + '">' +
				'</div>'
			);
		}

		reader.readAsDataURL(file);
	}

	function uploadFile(file) {
		if(location.host.indexOf("sitepointstatic") >= 0) {
			return;
		}

		var xhr = new XMLHttpRequest();

		if(xhr.upload) { 
			var o = $id("progress");

			var progress = o.appendChild(document.createElement("p"));
			
			progress.appendChild(document.createTextNode(file.name));

			xhr.upload.addEventListener("progress", function(e) {
				var pc = parseInt(100 - (e.loaded / e.total * 100));
				
				progress.style.backgroundPosition = pc + "% 0";
			}, false);

			xhr.onreadystatechange = function(e) {
				if(xhr.readyState == 4) {
					progress.className = (xhr.status == 200 ? "success" : "failure");
				}
			};

			xhr.open("POST", $id("upload").value + "/" + file.size + "/", true);
			xhr.setRequestHeader("X_FILENAME", file.name);
			xhr.send(file);
		}
	}

	function init() {
		var fileselect   = $id("fileselect"),
			filedrag     = $id("filedrag"),
			submitbutton = $id("submitbutton");

		fileselect.addEventListener("change", fileSelectHandler, false);

		var xhr = new XMLHttpRequest();
		
		if(xhr.upload) {
			filedrag.addEventListener("dragover", fileDragHover, false);
			filedrag.addEventListener("dragleave", fileDragHover, false);
			filedrag.addEventListener("drop", fileSelectHandler, false);
			filedrag.style.display = "block";
		}
	}

	if(window.File && window.FileList && window.FileReader) {
		init();
	}
})();