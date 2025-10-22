                            //param: type 
export default function ucwords(str: string){
    return str.replace(/\b\w/g, firstWord => firstWord.toUpperCase());
}