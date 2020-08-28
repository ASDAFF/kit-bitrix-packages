import React from 'react';
import BasketItem from "../basket-item/basket-item";
import './basket-items-list.scss';
import EmptyBasket from "../empty-basket/empty-basket";

export default class BasketItemsList extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        const {itemsBasket = [],
            itemsDelay = [],
            title,
            whatToShow,
            onInFavorite,
            onInBasket,
            onBtnMore,
            onBtnLess,
            onChangeCount,
            onBlurCount,
            onDeleteItem,
            onBtnReturns,
            linkToCatalog } = this.props;
        let basketProduct = null;

        switch (whatToShow) {
            case "basket":
                if(itemsBasket.length > 0) {
                    basketProduct = itemsBasket.map((item) => {
                        return (
                            <BasketItem params={item}
                                        onInFavorite={onInFavorite}
                                        whatToShow={whatToShow}
                                        key={item.PRODUCT_ID}
                                        title={title}
                                        onBtnMore={onBtnMore}
                                        onBtnLess={onBtnLess}
                                        onChangeCount={onChangeCount}
                                        onBlurCount={onBlurCount}
                                        onDeleteItem={onDeleteItem}
                                        onBtnReturns={onBtnReturns}
                            />
                        );
                    });
                    basketProduct.sort((prev, next) => {
                        return parseFloat(prev.key) - parseFloat(next.key);
                    });
                } else {
                    return <EmptyBasket title={title} isShow={'basket'} linkToCatalog={linkToCatalog}/>
                }
            break;

            case "favorite":
                if(itemsDelay.length > 0) {
                    basketProduct = itemsDelay.map((item) => {
                        return (
                            <BasketItem params={item}
                                        onInBasket={onInBasket}
                                        whatToShow={whatToShow}
                                        key={item.PRODUCT_ID}
                                        title={title}
                                        onBtnMore={onBtnMore}
                                        onBtnLess={onBtnLess}
                                        onChangeCount={onChangeCount}
                                        onBlurCount={onBlurCount}
                                        onDeleteItem={onDeleteItem}
                                        onBtnReturns={onBtnReturns}
                            />
                        );
                    });
                    basketProduct.sort((prev, next) => {
                        return parseFloat(prev.key) - parseFloat(next.key);
                    });
                } else {
                    return <EmptyBasket title={title} isShow={'favorite'} linkToCatalog={linkToCatalog}/>
                }
            break;

        }
        return (
          <ul className={`basket-items-list`}>
              {basketProduct}
          </ul>
        );
     }
}

