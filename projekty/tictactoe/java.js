function sprawdz(){
    let osoba1 = document.getElementById("osoba1").value.toLowerCase();
    let osoba2 = document.getElementById("osoba2").value.toLowerCase();

    let wynik = document.getElementById("wynik");

    if((osoba1 === "natalia" && osoba2 === "nicolas") || (osoba1 === "nicolas" && osoba2 === "natalia")){
        wynik.innerHTML = "100% ❤️ Pewniak";
    } else if((osoba1 === "natalia" && osoba2 === "alan") || (osoba1 === "alan" && osoba2 === "natalia")){
        wynik.innerHTML = "101% stara miłość nie rdzewieje";
    }else if((osoba1 === "szymon" && osoba2 === "nadia") || (osoba1 === "nadia" && osoba2 === "szymon")){
        wynik.innerHTML = "99% ❤️";
    } else {
        let procent = Math.floor(Math.random() * 101);
        wynik.innerHTML = procent + "% 🤔";
    }
}
