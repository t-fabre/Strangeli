/*######################### Fichier de l'objet Modal #########################*/
/* Version : 0.1 */

var eventList = []; // Liste des event ajouter par addEvent

/**
 * Constructeur de modal
 * @param  {String} pattern Type de patterne : notification, formulaire
 * @param  {Array} content Tableau de tout les attribut nécessaire pour chaque modal
 */
var modal = function ( id , pattern, content, nameModale ){

  var baseModal = new base(id);

  construct(pattern, content, nameModale, baseModal);

  this.show = function () {
    baseModal.show();
    addEvent();
  }

  this.hide = function () {
    removeEventList();
    baseModal.hide();
  }



 function construct(pattern, content, nameModale, baseModal){
   // {titre:[titre, type], message:[text], button:[[name, class, onclick],[name, class, onclick]]}

   if(pattern == "notification"){
     close(nameModale); // Création du bouton pour fermer le modal
     console.log(content);
     for(objet in content){
       switch (objet) {
          case "titre":
            title(content.titre[0], content.titre[1]);
            break;
          case "message":
            message(content.message);
            break;
          case "button":
            button(content.button);
            break;
       }
     }

   }

   // crétion de la croix pour fermer le modal
   function close(nameModale){
     var close = document.createElement("p");
     close.setAttribute("id","closeModal");
     close.setAttribute("onclick", nameModale+".hide()");
     close.appendChild(icon("icon-close"));
     baseModal.setElement(close);
   }

   // icon retourne la balise i de l'icone créer à partir de la classe que l'on indique en argument
   function icon(className){
     var i = document.createElement("i");
     i.setAttribute("class", className);
     i.setAttribute("aria-hidden", "true");
     return i;
   }

   // titre du modal ; type : notification (bleu) , warning (jaune) , error (rouge)
   function title(titre, type){
     let className = "modal-gen-standard";
     switch (type) {
 		  case 'notification':
 		    className = "modal-gen-standard";
 		    break;
 		  case 'warning':
 				className = "modal-gen-warning";
 				break;
 		  case 'error':
 				className = "modal-gen-error";
 		    break;
 		}

     var h5 = document.createElement("h5");
     h5.setAttribute("class", className);
     h5.textContent = titre;
     baseModal.setElement(h5);
   }

   // message du modal
   function message(text){
     var p = document.createElement("p");
     p.textContent = text;
     baseModal.setElement(p);
   }

   function button(array){
     var p = document.createElement("p");
     for(let i = 0; i <= (array.length - 1); i++){
       var button = document.createElement("button");
       button.textContent = array[i][0];
       button.setAttribute("class", "modal-message-btn " + array[i][1]);
       button.setAttribute("onclick", array[i][2]);
       p.appendChild(button);
     }
     baseModal.setElement(p);
   }
 }

}

/**
 * Base de modal
 * @param  {String} html id de la balise du document html qui va contenir le modal
 */
var base = function (html) {

  var elementHTML = document.getElementById(html);
  var objetSection = null;
  var objetDiv = null;

  constructBase();

  function constructBase() {
    objetSection = document.createElement("section");
    objetSection.setAttribute("class", "modal");
    objetSection.setAttribute("id", "section");
    eventList.push(["section","click",function(){modaltest.hide()}]);

    objetDiv = document.createElement("div");
    objetDiv.setAttribute("class", "modal-content1");
    objetSection.appendChild(objetDiv);
  }

  this.setElement = function (element) {
    objetDiv.appendChild(element);
  }

  this.show = function () {
    objetSection.innerHTML = "";
    elementHTML.innerHTML = "";
    objetSection.appendChild(objetDiv);

    elementHTML.appendChild(objetSection);
  }

  this.hide = function () {
    elementHTML.innerHTML = "";
  }
};

function removeEventList(){
  for(let i = 0; i<= (eventList.length - 1); i++){
    var obj = document.getElementById(eventList[i][0]);
    if (obj.detachEvent){
      //Est-ce IE ?
      obj.detachEvent("on" + eventList[i][1], eventList[i][2]); //Ne pas oublier le "on"
    }else{
      obj.removeEventListener(eventList[i][1], eventList[i][2], true);
    }
  }
  eventList = [];
}

function addEvent() {
    for(let i = 0; i<= (eventList.length - 1); i++){
      var obj = document.getElementById(eventList[i][0]);
      if(obj.attachEvent){
        //Est-ce IE ?
        obj.attachEvent("on" + eventList[i][1], eventList[i][2]); //Ne pas oublier le "on"
      }else{
        obj.addEventListener(eventList[i][1], eventList[i][2], true);
      }
    }

}
// <section id="modal" class="modal">
//     <div id="modalDiv" class="modal-content1 flex-centered-container">
//         <p class="close" id="closeModal"><i class="fa fa-times-circle-o" aria-hidden="true"></i></p>
//         <!-- titre notification, avertissement, erreur -->
//         <h5 id="titleNotification"></h5>
//         <!-- détail, message -->
//         <p id="messageNotification"></p>
//     </div>
// </section>
// function displayNotification (title,message,type,time) {
// 		let className = "";
//
// 		displayFlex(modalNotification,"flex","center","center");
//
// 		switch (type) {
// 		  case 'notification':
// 		    className = "modal-gen-standard";
// 		    break;
// 		  case 'warning':
// 				className = "modal-gen-warning";
// 				break;
// 		  case 'error':
// 				className = "modal-gen-error";
// 		    break;
// 		}
//
// 		var titleNotification = document.getElementById("titleNotification");
// 		titleNotification.className = className;
// 		titleNotification.textContent = title;
//
//     var messageNotification = document.getElementById("messageNotification");
//     messageNotification.innerHTML = message;
//
// 		modalNotification.style.opacity = 1;
// 		if(time){
// 	    setTimeout(function () {
// 	        modalNotification.style.opacity = 0;
// 	    },time);
// 		}
// }
