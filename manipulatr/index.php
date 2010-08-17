<? require_once('../../config/config.inc'); ?>

<!DOCTYPE html>

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name = "viewport" content = "user-scalable=no, width=device-width">
	<title>Manipulatr</title>
	<style>
.manipulatr{
position:relative;
width:900px;
margin:0 auto;
border:solid 1px #000;
}

.manipulatr div.group{
width:855px;
margin:0 auto;
}


.manipulatr img.photo{
margin:10px;
border:0;
width:75px;
height:75px;
}

.manipulatr .staging_panel {
position:absolute;
width:800px;
top:50px;right:50;left:50px;
background-color:#fff;
border:solid 1px #000;
-webkit-box-shadow: 10px 10px 5px rgba(0, 0, 0, 0.2);
border-radius: 5px; padding: 5px 5px 5px 3px;
}

.manipulatr .staging_panel table tr td.error {
display:none;
}

.manipulatr .staging_panel table tr.invalid td {
display:none;
}

.manipulatr .staging_panel table tr.invalid td.error {
display:table-cell;
color;#f00;
}
	</style>
</head>
<body>
<h1>Manipulatr</h1>
<P class="prototypecaption">Upload and Manipulate stuff</P>
<div class="manipulatr">
	<div class="group">
		Loading Flickr Photos...
	</div>
</div>
<script type="text/javascript" src="http://yui.yahooapis.com/combo?3.1.1/build/yui/yui-min.js"></script>
<script>
	YUI.add("gallery-flickr-api",function(a){YUI.namespace("flickrAPITransactions");var j="http://api.flickr.com/services/rest/",k={format:"json",clientType:"yui-3-flickrapi-module"},e="flapicb",f=YUI.flickrAPITransactions,c=function(){return true},i=function(){return true},g=function(){return true},b={last_response_id:0,id_map:{},response_map:{},setResponse:function(n,m){var l=b;l.response_map[n]=m;return l.response_map[n]},setId:function(o,n){var m=b,l=n;m.id_map[o]=l;m.last_response_id=l;return l}},d=function(n){var l="";for(var m in n){if(n.hasOwnProperty(m)){l+=m+"="+n[m]+"&"}}return l.substr(0,l.length-1)},h=function(p,m){var o=b,n=(o.response_map[o.id_map[m[0].tId]])?o.response_map[o.id_map[m[0].tId]][0]:null,l=[{params:m[0].data,data:n}].concat(m);if(n){if(n.stat==="ok"){return p.apply(a,l)}else{a.log(n.message,"error","flickrAPI");this.failure.apply(a,l)}}else{c.apply(a,l)}};a.flickrAPI={callMethod:function(n,q,l,s){s=s||{};q=q||{};var p=j;a.Object.each(q,function(v,t,u){u[t]=encodeURIComponent(v)});q=a.merge(a.config.flickrAPI,q);q.method=n;if(q.flickr_api_uri){p=q.flickr_api_uri;delete q.flickr_api_uri}if(a.Lang.isFunction(l)){l={success:l,failure:c,progress:i,timeout:g}}if(a.Lang.isObject(l)){s=a.merge(s,{onSuccess:function(){a.fire("flickrAPI:success");return h.apply(l,[l.success,arguments,1])},onFailure:function(){if(b.response_map[b.id_map[arguments[0].tId]]!==null){a.log("Your request (ID:"+arguments[0].tId+") has failed.","error","flickrAPI");a.fire("flickrAPI:failure");return h.apply(l,[l.failure,arguments,0])}},onProgress:function(){a.fire("flickrAPI:progress");return h.apply(l,[l.progress,arguments,null])},onTimeout:function(){a.log("Your request (ID:"+arguments[0].tId+") has timed out","error","flickrAPI");f[e+b.id_map[arguments[0].tId]]=function(){a.log("A response callback was fired but suppressed because of a timeout enforced by configuration.","warn","flickrAPI")};b.response_map[b.id_map[arguments[0].tId]]=null;a.Get.abort(arguments[0].tId);a.fire("flickrAPI:timeout");return h.apply(l,[l.timeout,arguments,0])}})}s.scope=a;var r=a.merge(s.data,k,q);s.data=r;var o=b.last_response_id+1,m=a.Get.script(p+"?"+d(r)+"&jsoncallback=YUI.flickrAPITransactions."+e+o+"&cachebust="+(new Date()).getTime(),s);b.setId(m.tId,o);f[e+o]=function(t){var u=b.setResponse(o,arguments);if(u){a.later(1000,a,function(){delete f[e+o]})}};return m}}},"gallery-a-002",{requires:["event"]});
	YUI().use('node','gallery-flickr-api','substitute',function(Y) {
		
		var manipulatr_node = Y.one('.manipulatr div.group');
		
		/*
		*  Module which will listen for drag drop
		*  upload files
		*/
		function Uploader(node) {
			
			//Create interface
			Y.uploader = {
				file_stageing : {},
				active_upload : false,
				validateFile : function(file) {
					if(file.fileSize > 0 && file.type.split('/')[0] === 'image') {
						return file;
					} else {
						return false;
					}
				},
				showStagingPanel : function() {
					var panel_node = manipulatr_node.one('.staging_panel');
					
					/*
					*  Generate the panel if needed
					*/
					if(!panel_node) {
						manipulatr_node.append('<div class="staging_panel"><h2>Would you like to order and name these photos before you save them?</h2><div class="photos"></div><div class="actions"><button class="save">Save them!</button><button class="cancel">Nevermind</button></div></div>');
						panel_node = manipulatr_node.one('.staging_panel');
					}
					
					/*
					*  Generate the panel if needed
					*/
					var photos_node   = panel_node.one('.photos')
					    photo_table   = '<table class="list"><tr class="header"><td></td><td>Title</td><td>Description</td></tr>',
					    staging_clean = [];
					
					for(var i in file_stageing) {
						if(file_stageing.hasOwnProperty(i)) {
							var photo = file_stageing[i];
							photo_table += Y.substitute('<tr class="{className} {title}"><td></td><td><input type="text" name="title_{title}" value="{title}"></td><td><textarea name="description_{title}"></textarea></td><td class="error" colspan="2">{title} is not a valid file.</td></tr>',{
								title     : i,
								className : (photo) ? 'photo-line' : 'photo-line invalid'
							});
							
							if(photo) {
								staging_clean.push(file_stageing[i]);
							}
						}
					}
					photo_table += '</table>';
					
					photos_node.set('innerHTML',photo_table);
					
					/*
					*  Attach listeners to panel
					*/
					var actions_node = photos_node.one('.actions');
					
					panel_node.one('.save').on('click',function(e) {
						e.preventDefault();
						var rows = panel_node.all('tr');
						
						Y.photoGroupDisplay.displayStagedPhotos(staging_clean);
					});
					
					panel_node.one('.cancel').on('click',function(e) {
						e.preventDefault();
						var panel_el = panel_node._node;
						
						panel_el.parentNode.removeChild(panel_el);
					});
					
					Y.on('PhotoGroupDisplay:pendingFilesDisplayed',function() {
						var panel_el = panel_node._node;
						
						panel_el.parentNode.removeChild(panel_el);
					});
				},
				attachEvents : function(node) {
					manipulatr_node.on("dragenter", function(e) {
						e.stopPropagation();  
					  	e.preventDefault();
					},Y.uploader);
					manipulatr_node.on("dragover", function(e) {
						e.stopPropagation();  
					  	e.preventDefault();
					},Y.uploader);
					manipulatr_node.on("drop", function(e) {
						e.stopPropagation();  
					  	e.preventDefault();
						var data  = e._event.dataTransfer,
						    files = data.files;

						if(files.length) {
							this.active_upload = true;
							
							/*
							*  Clear current stage
							*/
							file_stageing = {};
							
							//Add files to file_staging
							for(var i=0,l=files.length;l>i;i++) {
								var file = this.validateFile(files[i]);
								
								file_stageing[files[i].name] = file;
							}
							
							this.showStagingPanel();	
						}
					},Y.uploader);
				}
			};
			
			/*
			*  Initialize modules
			*/
			Y.uploader.attachEvents(node);
		};
			
		/*
		*  Gets a group of flickr photos
		*/
		function FlickrPhotos() {
			
			Y.config.flickrAPI = Y.config.flickrAPI || {};
			Y.config.flickrAPI.api_key = '<?=$config['manipulatr']['api_key']?>';
			
			//Create interface
			Y.flickrPhotos = {
				cache : null,
				getImageSrc : function(photo) {
					return Y.substitute('http://farm{farm}.static.flickr.com/{server}/{id}_{secret}_s.jpg',photo);
				},
				update : function() {
					var flickrPhotos = this;
					
					Y.flickrAPI.callMethod('flickr.people.getPublicPhotos',{
						user_id  : '<?=$config['manipulatr']['user_id']?>', //Always bizzarro sp
						per_page : 50,
						page     : 1
					},function(call_back) {
						var response = call_back.data;
						flickrPhotos.cache = response.photos;
						
						Y.fire('flickrPhotos:cacheUpdated',{cache:flickrPhotos.cache});
					})
				}
			};
	
			/*
			*  Initialize modules
			*/
			Y.flickrPhotos.update();
		
		};
		
		/*
		*  Manages the photo group display
		*/
		function PhotoGroupDisplay(container_node) {
			
			//Create interface
			Y.photoGroupDisplay = {
				clear : function() {
					container_node.set('innerHTML','');
				},
				displayStagedPhotos : function(broser_drop_array) {
					
					for(var i=0,l=broser_drop_array.length;l>i;i++) {
						var file = broser_drop_array[i]
						    img  = document.createElement("img");
						
						img.className = "photo pending";
						img.file=file;
						console.log(file);
						manipulatr_node.prepend(img);
						
						var pending_node = manipulatr_node.one('img.pending');
						
						//file.FileConstructor()
							//reader.onload = (function(aImg) { return function(e) { aImg.setAttribute('src', e.target.result); }; })(pending_node);
							//reader.readAsDataURL(file);
					}
					
					Y.fire('PhotoGroupDisplay:pendingFilesDisplayed');
				},
				displayFlickrPhotos : function(flickr_photo_array) {
					var photo_item_template = '<img src="{src}" class="photo">',
					    temp_output         = '';
					
					for(var i=0,l=flickr_photo_array.length;l>i;i++) {
						var photo = flickr_photo_array[i];
						temp_output += Y.substitute(
							photo_item_template,
							{src:Y.flickrPhotos.getImageSrc(photo)}
						);
					}
					
					container_node.set('innerHTML',temp_output);
				}
			};
	
			/*
			*  Initialize modules
			*/
			if(Y.flickrPhotos.cache.photo.length) {
				Y.photoGroupDisplay.displayFlickrPhotos(Y.flickrPhotos.cache.photo);
			} else {
				Y.error('Booo! There are no photos in the cache. Did you update Y.flickrPhotos?');
			}
		
		};
		
		//Init modules
		Uploader(manipulatr_node);
		FlickrPhotos();
		
		Y.on('flickrPhotos:cacheUpdated', function() {
			PhotoGroupDisplay(manipulatr_node);
		});

	});
</script>
</body>
</html>
