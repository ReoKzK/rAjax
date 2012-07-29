
	/** --------------------------------- **
	 *               rAjax                 *
	 * ----------------------------------- *
	 * File : rajax.js                     *
	 * Simple Ajax Engine                  *
	 * @author Reo Fox reo.fox@gmail.com   *
	 * @version 0.1.1 beta <2012-07-29>    *
	 ** --------------------------------- **
	*/

	function rAjax ()
	{
		var thisObj = new Object();
		
		thisObj.xmlHttpRequest = null;
		
		thisObj.requests = new Array();
		
		thisObj.responseType = null;
		thisObj.onLoad = null;
		thisObj.onError = null;
		thisObj.onLoading = null;
		
		thisObj.lang = null;
		thisObj.debugMode = false;
		
		/**
		 *  run()
		 *  rAjax run method.
		 */
		thisObj.run = function()
		{
			thisObj.xmlHttpRequest = thisObj.createXmlHttpRequestObject();
			
			if ( thisObj.debugMode && thisObj.xmlHttpRequest == null )
			{
				thisObj.handleError(thisObj._("error_create_xmlhttp_req_obj"));
			}
		}

		
		/**
		 *  open(url, method, responseType, onLoad, onError, onLoading)
		 *  Request method.
		 *  @param url			: Request URL
		 *  @param method		: Requested method (get/post)
		 *  @param responseType	: Type of response (text/xml)
		 *  @param onLoad		: Function to execute when progress is done
								  eg. function(data){alert(data);}
		 *  @param onError		: Function to execute when error has occured (user friendly, professional is viewed when debugMode is on)
								  eg. function(data){alert(data);}
		 *  @param onLoading	: Function to execute when data is loading
								  eg. function(){ document.body.style.cursor = "progress"; }
		 *  @param args			: Request arguments (if method is POST)
		 */
		thisObj.open = function (url, method, responseType, onLoad, onError, onLoading, args)
 		{
 			if (thisObj.xmlHttpRequest)
 			{
				/*if ( responseType != null )
					thisObj.responseType = responseType;
				else
					thisObj.responseType = "text";
				
				if ( method != null )
					method = method.toUpperCase();
			
				if ( typeof onLoad == "function" )
					thisObj.onLoad = onLoad;
				
				if ( typeof onError == "function" )
					thisObj.onError = onError;
				
				if ( typeof onLoading == "function" )
					thisObj.onLoading = onLoading;
				*/
				
				if (url)
				{
					// - Add new request to cache -
					var newRequest = {
						url:       url,
						method:    method.toUpperCase(),
						type:      responseType,
						onLoad:    onLoad,
						onError:   onError,
						onLoading: onLoading,
						handler:   thisObj.open
					};
					
					if ( args != null )
						newRequest.args = args;
				
					thisObj.requests.push(newRequest);
				}
				
				//alert(newRequest.url + ", " + newRequest.method);
				
 				// - Zablokowanie wykonania żądania gdy obiekt -
 				// - thisObj.xmlHttpRequest jest zajęty.
 		
 				if ( !( thisObj.xmlHttpRequest.readyState == 0 || thisObj.xmlHttpRequest.readyState == 4 ) )
 				{
					//thisObj.handleError(thisObj._("error_xmlhttp_req_obj_busy"));
				}
			
				else if ( (thisObj.xmlHttpRequest.readyState == 0 || thisObj.xmlHttpRequest.readyState == 4) && thisObj.requests.length > 0 )
				{
 					try
 					{
						// - Shift request from cache -
					
						var request = thisObj.requests.shift();
					
						if ( request.type != null )
							thisObj.responseType = request.type;
						else
							thisObj.responseType = "text";
					
						if ( typeof request.onLoad == "function" )
							thisObj.onLoad = request.onLoad;
						else
							thisObj.onLoad = null;
				
						if ( typeof request.onError == "function" )
							thisObj.onError = request.onError;
						else
							thisObj.onError = null;
				
						if ( typeof request.onLoading == "function" )
							thisObj.onLoading = request.onLoading;
						else
							thisObj.onLoading = null;
					
						var sendString = ( request.args != null ) ? request.args : null;
					
						// - Send request -
					
 						thisObj.xmlHttpRequest.open(request.method, request.url, true);
						
						if ( request.method == "POST")
							thisObj.xmlHttpRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
						
 						thisObj.xmlHttpRequest.onreadystatechange = function() {
							thisObj.handleRequestStateChange();
 						}
 						thisObj.xmlHttpRequest.send(sendString);
 					}

 					catch (exception)
					{
						if ( typeof thisObj.onError == "function" )
							thisObj.onError();
					
						if ( thisObj.debugMode )
							thisObj.handleError(thisObj._("error_connect_server") + ": " + exception.toString());
					}
				}
			
				// - Nie zostanie wywołany link do strony php -
			
				return true;
 			}
 		
 			// - Zostanie wywołany link do strony php -
 		
 			else
 				return false;
 		}
		
		/**
		 *  sendForm(form, url, responseType, onLoad, onError, onLoading)
		 *  Sends form via AJAX.
		 *  @param form			: Form object
		 *  @param url			: Request URL
		 *  @param responseType	: Type of response (text/xml)
		 *  @param onLoad		: Function to execute when progress is done
								  eg. function(data){ alert(data); }
		 *  @param onError		: Function to execute when error has occured (user friendly, professional is viewed when debugMode is on)
								  eg. function(){ alert("Sorry, an error has occured."); }
		 *  @param onLoading	: Function to execute when data is loading
								  eg. function(){ document.body.style.cursor = "progress"; }
		 */
		thisObj.sendForm = function (form, url, responseType, onLoad, onError, onLoading)
 		{
 			if (thisObj.xmlHttpRequest)
 			{
				if ( typeof form == "object" )
				{/*
					if ( responseType != null )
						thisObj.responseType = responseType;
					else
						thisObj.responseType = "text";
			
					if ( typeof onLoad == "function" )
						thisObj.onLoad = onLoad;
					else
						thisObj.onLoad = null;
					
					if ( typeof onError == "function" )
						thisObj.onError = onError;
					else
						thisObj.onError = null;
					
					if ( typeof onLoading == "function" )
						thisObj.onLoading = onLoading;
					else
						thisObj.onLoading = null;
					*/
					// - Get all form elements -
					
					var args = new Array();
					
					for ( elem in form.elements )
					{
						// - Ignore element without ID and name -
						if ( form.elements[elem].id == "" && form.elements[elem].name == "" )
							continue;
					
						switch ( form.elements[elem].type )
						{
							case "text" :
							case "password" :
							case "hidden" :
							case "textarea" :
							case "submit" :
								args.push(encodeURIComponent( (form.elements[elem].id != "") ? form.elements[elem].id : form.elements[elem].name ) + "=" + encodeURIComponent(form.elements[elem].value));
								break;
							
							case "checkbox" :
								args.push(encodeURIComponent( (form.elements[elem].id != "") ? form.elements[elem].id : form.elements[elem].name ) + "=" + (form.elements[elem].checked ? "on" : "off"));
								break;
							
							case "radio" :
								if ( form.elements[elem].checked == true )
									args.push(encodeURIComponent(form.elements[elem].name) + "=" + form.elements[elem].value);
								break;
							
							case "select-one" :
								args.push(encodeURIComponent( (form.elements[elem].id != "") ? form.elements[elem].id : form.elements[elem].name ) + "=" + form.elements[elem].options[form.elements[elem].selectedIndex].value);
								break;
							
							case "select-multiple" :
								for ( var i = 0; i < form.elements[elem].options.length; i++ )
								{
									if ( form.elements[elem].options[i].selected )
										args.push(encodeURIComponent( (form.elements[elem].id != "") ? form.elements[elem].id : form.elements[elem].name ) + "=" + form.elements[elem].options[i].value);
								}
								break;
							
							default :
								break;
						}
					}
					
					// - Create 'send string' -
					
					var sendString = "";
					
					for ( var i = 0; i < args.length; i++ )
					{
						sendString += args[i];
						
						if ( i + 1 < args.length )
							sendString += "&";
					}
					
					//alert(sendString);
					//return false;
					
					// - Add new request to cache -
					var newRequest = {
						url:        url,
						method:     "POST",
						type:       responseType,
						args:       sendString,
						onLoad:     onLoad,
						onError:    onError,
						onLoading:  onLoading,
						handler:    thisObj.sendForm
					};
				
					thisObj.requests.push(newRequest);
				}
				
				
				// - Zablokowanie wykonania żądania gdy obiekt -
				// - thisObj.xmlHttpRequest jest zajęty.
		
				if ( !( thisObj.xmlHttpRequest.readyState == 0 || thisObj.xmlHttpRequest.readyState == 4 ) )
 				{
					//thisObj.handleError(thisObj._("error_xmlhttp_req_obj_busy"));
				}
			
				else if ( (thisObj.xmlHttpRequest.readyState == 0 || thisObj.xmlHttpRequest.readyState == 4) && thisObj.requests.length > 0 )
				{
					try
					{
						// - Shift request from cache -
					
						var request = thisObj.requests.shift();
					
						if ( request.type != null )
							thisObj.responseType = request.type;
						else
							thisObj.responseType = "text";
					
						if ( typeof request.onLoad == "function" )
							thisObj.onLoad = request.onLoad;
						else
							thisObj.onLoad = null;
				
						if ( typeof request.onError == "function" )
							thisObj.onError = request.onError;
						else
							thisObj.onError = null;
				
						if ( typeof request.onLoading == "function" )
							thisObj.onLoading = request.onLoading;
						else
							thisObj.onLoading = null;
					
						var args = ( request.args != null ) ? request.args : null;
					
						thisObj.xmlHttpRequest.open("POST", request.url, true);
						thisObj.xmlHttpRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
						thisObj.xmlHttpRequest.onreadystatechange = function() {
							thisObj.handleRequestStateChange();
						}
						thisObj.xmlHttpRequest.send(args);
					}

					catch (exception)
					{
						if ( typeof thisObj.onError == "function" )
							thisObj.onError();
						
						if ( thisObj.debugMode )
							thisObj.handleError(thisObj._("error_connect_server") + ": " + exception.toString());
					}
				}
				
				return true;
			}
			
			else
				return false;
		}
		
		/**
		 *  handleRequestStateChange()
		 *  Handling request state change.
		 */
		thisObj.handleRequestStateChange = function()
		{
			if ( thisObj.xmlHttpRequest.readyState == 4 )
			{
				if ( thisObj.xmlHttpRequest.status == 200 || window.location.href.indexOf("http") == -1 )
				{
					try
					{
						thisObj.handleServerResponse();
					}
					
					catch (exception)
					{
						if ( typeof thisObj.onError == "function" )
							thisObj.onError();
						
						if ( thisObj.debugMode )
							thisObj.handleError(thisObj._("error_handle_response") + ":\n" + exception.toString());
					}
				}
			
				else
				{
					if ( typeof thisObj.onError == "function" )
						thisObj.onError();
				
					if ( thisObj.debugMode )
						thisObj.handleError(thisObj._("error_handle_response_status") + ": " + thisObj.xmlHttpRequest.statusText);
				}
			}
	
			else
			{
				if ( typeof thisObj.onLoading == "function" )
					thisObj.onLoading();
			}
		}
		
		/**
		 *  handleServerResponse()
		 *  Handling server response.
		 */
		thisObj.handleServerResponse = function()
		{
			if ( thisObj.responseType == "text" )
			{
				// - Read the response -
				var response = thisObj.xmlHttpRequest.responseText;
		
				// - Sprawdzenie czy nie przesłano opisu błędu. -
				//thisObj.checkIfTextError(response);
			}
			
			else if ( thisObj.responseType == "xml" )
			{
				var response = thisObj.xmlHttpRequest.responseXML;
		
				// - Check if XML document is valid. -
				thisObj.validateXml(response);
			}
			
			else
			{
				response = null;
			}
			
			// - Execute onLoad function -
			thisObj.onLoad(response);
			
			// - Check if cache isn't empty -
			thisObj.checkCache();
		}
		
		/**
		 *  createXmlHttpRequestObject()
		 *  Creates xmlHttpRequestObject.
		 */
		thisObj.createXmlHttpRequestObject = function()
		{
			var xmlHttp;
	
			// - Próbuje utworzyć obiekt XMLHttpRequest.
			// - Przeglądarki Mozilla, Firefox, Netscape, IE 7.

			try
			{
				xmlHttp = new XMLHttpRequest();
			}
		
			catch(e)
			{
				// - Próbuje utworzyć obiekt ActiveX.
				// - Internet Explorer 6 i w dół.
		
				var xmlHttpVersions = new Array(
					"MSXML2.XMLHTTP.6.0",
					"MSXML2.XMLHTTP.5.0",
					"MSXML2.XMLHTTP.4.0",
					"MSXML2.XMLHTTP.3.0",
					"MSXML2.XMLHTTP",
					"Microsoft.XMLHTTP"
				);
		
				// - Sprawdza każdy identyfikator programu aż jeden zadziała. -
				
				for ( var i = 0; i < xmlHttpVersions.length && !xmlHttp; i++ )
				{
					try { xmlHttp = new ActiveXObject(xmlHttpVersions[i]); }
					catch(e) { }
				}
			}
		
			// - Zwraca obiekt lub w razie jego nieutworzenia null. -
	
			if (xmlHttp)
			{
				return xmlHttp;
			}
	
			else
			{
				return null;
			}
		}
	
		/**
		 *  handleError(error)
		 *  Handles internal error - used in debug mode.
		 */
		thisObj.handleError = function(error)
		{
			alert(error);
		}
	
		/**
		 *  validateXml()
		 *  Checking if requested XML file is valid.
		 */
		thisObj.validateXml = function(xmlResponse)
		{
			// - Errors cought by IE & Opera. -

			if (!xmlResponse || !xmlResponse.documentElement)
				throw (thisObj._("error_xml_incorrect_struct") + ":\n" + thisObj.xmlHttpRequest.responseText);

			// - Errors cought by Firefox. -

			var rootNodeName = xmlResponse.documentElement.nodeName;

			if ( rootNodeName == "parsererror" )
				throw (thisObj._("error_xml_incorrect_struct") + ":\n" + thisObj.xmlHttpRequest.responseText);
		}
		
		/**
		 *  checkCache()
		 *  Checks if there are requests in cache
		 */
		thisObj.checkCache = function()
		{
			if ( thisObj.requests.length > 0 )
			{
				if ( thisObj.requests[0].handler == thisObj.open )
				{
					thisObj.open();
					//setTimeout("thisObj.open();", 100);
					//document.write(thisObj.requests[0].url);
					//alert("Handler: open()");
				}
				
				else if ( thisObj.requests[0].handler == thisObj.sendForm )
				{
					thisObj.sendForm();
				}
			}
		}
		
		/**
		 *  _(str)
		 *  Gets translated string from array thisObj.lang
		 */
		thisObj._ = function(str)
		{
			if ( thisObj.lang != null && typeof thisObj.lang[str] != "undefined" )
				return thisObj.lang[str];
			else
				return str;
		}
		
		return thisObj;
		
	}
