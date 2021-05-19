var st1 = document.getElementById("st1");
var st2 = document.getElementById("st2");
var st3 = document.getElementById("st3");
var st4 = document.getElementById("st4");
var st5 = document.getElementById("st5");
var rating = document.getElementById("rating");

st1.addEventListener('click', ()=>{
    st1.style.color = "yellow";
    st2.style.color = "gray";
    st3.style.color = "gray";
    st4.style.color = "gray";
    st5.style.color = "gray";
    rating.value = "1";
});

st2.addEventListener('click', ()=>{
    st1.style.color = "yellow";
    st2.style.color = "yellow";
    st3.style.color = "gray";
    st4.style.color = "gray";
    st5.style.color = "gray";
    rating.value = "2";
});

st3.addEventListener('click', ()=>{
    st1.style.color = "yellow";
    st2.style.color = "yellow";
    st3.style.color = "yellow";
    st4.style.color = "gray";
    st5.style.color = "gray";
    rating.value = "3";
});

st4.addEventListener('click', ()=>{
    st1.style.color = "yellow";
    st2.style.color = "yellow";
    st3.style.color = "yellow";
    st4.style.color = "yellow";
    st5.style.color = "gray";
    rating.value = "4";
});

st5.addEventListener('click', ()=>{
    st1.style.color = "yellow";
    st2.style.color = "yellow";
    st3.style.color = "yellow";
    st4.style.color = "yellow";
    st5.style.color = "yellow";
    rating.value = "5";
});