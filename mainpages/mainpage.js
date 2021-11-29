"use strict";
const icon = document.querySelector(".profile");
const list = document.querySelector(".list");

icon.addEventListener("click", function () {
  list.classList.toggle("show");
});

const sidebarIcon = document.querySelector(".hamburger");
const sideBar = document.querySelector(".sidebar");

sidebarIcon.addEventListener("click", function () {
  sideBar.classList.toggle("flexshow");
});

// Navbar Post BTN Modal
let postBtn = document.querySelector(".postbtn");
let closeIconPost = document.querySelector(".closeIconPost");
let modalPost = document.querySelector(".postModal");

postBtn.addEventListener("click", function () {
  modalPost.classList.toggle("hidden");
});

closeIconPost.addEventListener("click", function () {
  modalPost.classList.toggle("hidden");
});

modalPost.addEventListener("click", (e) => {
  if (e.target !== e.currentTarget) {
    console.log("test");
  } else {
    modalPost.classList.toggle("hidden");
  }
});

const like = document.querySelectorAll(".like");
const unlike = document.querySelectorAll(".unlike");

for (let i = 0; i < like.length; i++) {
  like[i].addEventListener("click", function () {
    like[i].classList.toggle("unshow");
    unlike[i].classList.toggle("unshow");
  });
}

for (let j = 0; j < unlike.length; j++) {
  unlike[j].addEventListener("click", function () {
    like[j].classList.toggle("unshow");
    unlike[j].classList.toggle("unshow");
  });
}
