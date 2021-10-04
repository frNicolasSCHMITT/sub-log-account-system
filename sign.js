// SIGN BOX

function moveDrawligne(oEvent) {
  var oCanvas = oEvent.currentTarget,
    oCtx = null,
    oPos = null;
  if (oCanvas.bDraw == false) {
    return false;
  } //if
  oPos = getPosition(oEvent, oCanvas);
  oCtx = oCanvas.getContext("2d");

  //dessine
  oCtx.strokeStyle = "#000000";
  oCtx.lineWidth = 3;
  oCtx.beginPath();
  oCtx.moveTo(oCanvas.posX, oCanvas.posY);
  oCtx.lineTo(oPos.posX, oPos.posY);
  oCtx.stroke();

  oCanvas.posX = oPos.posX;
  oCanvas.posY = oPos.posY;
} //fct

function getPosition(oEvent, oCanvas) {
  var oRect = oCanvas.getBoundingClientRect(),
    oEventEle = oEvent.changedTouches ? oEvent.changedTouches[0] : oEvent;
  return {
    posX:
      ((oEventEle.clientX - oRect.left) / (oRect.right - oRect.left)) *
      oCanvas.width,
    posY:
      ((oEventEle.clientY - oRect.top) / (oRect.bottom - oRect.top)) *
      oCanvas.height,
  }; //
} //fct

function downDrawligne(oEvent) {
  oEvent.preventDefault();
  var oCanvas = oEvent.currentTarget,
    oPos = getPosition(oEvent, oCanvas);
  oCanvas.posX = oPos.posX;
  oCanvas.posY = oPos.posY;
  oCanvas.bDraw = true;
  capturer(false);
} //fct

function upDrawligne(oEvent) {
  var oCanvas = oEvent.currentTarget;
  oCanvas.bDraw = false;
  capturer(true);
} //fct

function initCanvas() {
  var oCanvas = document.getElementById("canvas");
  oCanvas.bDraw = false;
  oCanvas.width = 200;
  oCanvas.height = 150;
  oCtx = oCanvas.getContext("2d");
  oCanvas.addEventListener("mousedown", downDrawligne);
  oCanvas.addEventListener("mouseup", upDrawligne);
  oCanvas.addEventListener("mousemove", moveDrawligne);
  oCanvas.addEventListener("touchstart", downDrawligne);
  oCanvas.addEventListener("touchend", upDrawligne);
  oCanvas.addEventListener("touchmove", moveDrawligne);
} //fct

/**
 * Récupère le canva sous forme d'image
 * si l'image des plus grande que le canvas
 * c'est que vous avez oublié de presiser la taille du canva en javascript
 * oCanvas.width = 200 ;
 * oCanvas.height = 150;
 */
function capturer(bAction) {
  var oCapture = document.getElementById("capture");
  oCapture.innerHTML = "";
  if (bAction == true) {
    var oImage = document.createElement("img"),
      oCanvas = document.getElementById("canvas");
    oImage.src = oCanvas.toDataURL("image/png");
    oCapture.appendChild(oImage);
  } //if
} //fct

/**
 * Vide les dessin du canvas
 */
function nettoyer(oEvent) {
  var oCanvas = document.getElementById("canvas"),
    oCtx = oCanvas.getContext("2d");
  oCtx.clearRect(0, 0, oCanvas.width, oCanvas.height);
  capturer(false);
} //fct

document.addEventListener("DOMContentLoaded", function () {
  initCanvas();
  document.getElementById("bt-clear").addEventListener("click", nettoyer);
});

//récupération des données

// Accédez à l'élément form …

var form = document.getElementById("myForm");
// … et prenez en charge l'événement submit.
form.addEventListener("submit", function (event) {
  var XHR = new XMLHttpRequest();

  // Liez l'objet FormData et l'élément form
  var FD = new FormData(form);

  var temp = document.getElementById("capture").firstChild.getAttribute("src");
  var imgB64 = temp.split("data:image/png;base64,").pop();

  //  FD.append('prenom','toto');
  FD.append("data", imgB64);

  // Définissez ce qui se passe si la soumission s'est opérée avec succès
  XHR.addEventListener("load", function (event) {
    //alert(event.target.responseText);
  });

  // Definissez ce qui se passe en cas d'erreur
  XHR.addEventListener("error", function (event) {
    alert("Oups! Quelque chose s'est mal passé.");
  });

  // Configurez la requête
  XHR.open("POST", "account.php");

  // Les données envoyées sont ce que l'utilisateur a mis dans le formulaire
  XHR.send(FD);

  event.preventDefault();
});
