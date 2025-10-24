export class wishlistElement {
    constructor(type, elementId, elementName, linkCoverImg, price, salePrice, onSale, gift, addToCarturl) {
        this.type = type;
        this.elementId = elementId;
        this.id = elementId; 
        this.elementName = elementName;
        this.linkCoverImg = linkCoverImg;
        this.price = price;
        this.salePrice = salePrice;
        this.onSale = onSale;
        this.gift = gift;
        this.addToCarturl = addToCarturl;
    }
}