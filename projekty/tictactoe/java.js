function sprawdz(){
    let osoba1 = document.getElementById("osoba1").value.trim().toLowerCase();
    let osoba2 = document.getElementById("osoba2").value.trim().toLowerCase();

    let wynik = document.getElementById("wynik");

    if(
        (osoba1 === "natalia" && osoba2 === "nicolas") ||
        (osoba1 === "nicolas" && osoba2 === "natalia")
    ){
        wynik.innerHTML = "100% ❤️ Pewniak";
    } else {
        let procent = Math.floor(Math.random() * 101);
        wynik.innerHTML = procent + "% 🤔";
    }
}
