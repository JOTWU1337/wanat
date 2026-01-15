function sprawdz(){
    let osoba1 = document.getElementById("osoba1").value;
    let osoba2 = document.getElementById("osoba2").value;
    let wynik = document.getElementById("wynik");
    if((osoba1=="Natalia" && osoba2=="Nicolas") || (osoba1=="Nicolas" && osoba2=="Natalia")){
        wynik.innerHTML = "100% ❤️";
    } else {
        let procent = Math.floor(Math.random() * 101);
        wynik.innerHTML = procent + "% 🤔";
    }
}
