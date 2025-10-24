<html>
   <head>
       <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
       <script>
           function resize_image(input, width, height) {
               if (input.files[0].type.match(/image.*/)) {
                   $("#message").html("");

                    var canvas = $("#resizer")[0].getContext('2d');    
                    canvas.canvas.width = width;
                    canvas.canvas.height = height;

                                // If image's aspect ratio is less than canvas's we fit on height
                    // and place the image centrally along width
                    
                   

                   var img = new Image;
                   img.src = URL.createObjectURL(input.files[0]);
                    
                   img.onload = function() {
                       var imageAspectRatio = img.width / img.height;   
                       var canvasAspectRatio =canvas.canvas.width / canvas.canvas.height;
                       var renderableHeight, renderableWidth, xStart, yStart;

                       if(imageAspectRatio < canvasAspectRatio) {
                            renderableHeight = canvas.canvas.height;
                            renderableWidth = img.width * (renderableHeight / img.height);
                            xStart = (canvas.canvas.width - renderableWidth) / 2;
                            yStart = 0;
                        }

                        // If image's aspect ratio is greater than canvas's we fit on width
                        // and place the image centrally along height
                        else if(imageAspectRatio > canvasAspectRatio) {
                            renderableWidth = canvas.canvas.width
                            renderableHeight = img.height * (renderableWidth / img.width);
                            xStart = 0;
                            yStart = (canvas.canvas.height - renderableHeight) / 2;
                        }

                        // Happy path - keep aspect ratio
                        else {
                            renderableHeight = canvas.canvas.height;
                            renderableWidth = canvas.canvas.width;
                            xStart = 0;
                            yStart = 0;
                        }
                        canvas.drawImage(img, xStart, yStart, renderableWidth, renderableHeight);

                    //   canvas.drawImage(img, 0, 0, width, height);
                   }

                   $('#sender').show();
               } else {
                   $("#message").html("File not a image");
               }
           }

           function send_image() {
               var image_base64 = $("#resizer")[0].toDataURL();

               $.post(
                   "acaoDoc.php",
                   { imgBase64: image_base64 },
                   function(response) {
                       $("#message").html(response);
                   }
               );
           }
       </script>
       <style>
           #resizer { display: none; }
           #sender { display: none; }
       </style>
   </head>
   <body>
       <div>
           <input type="file" id="input" onchange="resize_image(this, 600, 480);" />
       </div>
       <div>
           <canvas id="resizer"></canvas>
           <div id="message"></div>
           <input type="button" id="sender" value="send" onclick="send_image();" />
       </div>
   </body>
</html>