import React from 'react';
import './basket-item.scss';

import QuantityCount from "../quantity-count/quantity-count";
import ItemParamsList from "../item-params-list/item-params-list";

export default class BasketItem extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            isRemove: null
        };

        this.onBtnRemove = () => {
            this.setState({
                isRemove: true
            });
            BX.onCustomEvent('OnBasketChange');
        };

    }


    render() {
        const {params,
            title,
            whatToShow,
            onInFavorite,
            onInBasket,
            onBtnMore,
            onBtnLess,
            onChangeCount,
            onDeleteItem,
            onBtnReturns,
            onBlurCount} = this.props;
        if (this.state.isRemove) {
            return null;
        }
        if(this.props.params.IS_REMOVED) {
            return (
              <li className={`basket-item-deleted`}>
                  <p className={`basket-item-deleted__title-removed`}>{title.REACT_BASKET_PRODUCT_TITLE} <a href={params.DETAIL_PAGE_URL}>{params.NAME}</a> {whatToShow === 'basket' ?
                      title.REACT_BASKET_DELETED_FROM_CART : title.REACT_BASKET_DELETED_FROM_DELAY}</p>
                  <button className={`basket-item-deleted__buttons-return`}
                          onClick={() => {onBtnReturns(params.PRODUCT_ID)}}>
                      {title.REACT_BASKET_RETURN}
                  </button>
                  <button className={`basket-item-deleted__buttons-remove`}
                          onClick={this.onBtnRemove}>
                      <svg className="basket-item-deleted__buttons-remove-icon" width="18" height="16">
                          <use xlinkHref="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_cancel_medium"></use>
                      </svg>
                  </button>
              </li>
            );
        }
        return (
            <li className={`basket-item`}>
                <div className={`basket-item__img`}>
                    <img src={params.PICTURE_SRC} alt={params.imgAlt}/>
                </div>
                <div className={`basket-item__description`}>
                    <a href={params.DETAIL_PAGE_URL} className={`basket-item__description-title`}>{params.NAME}</a>
                    <ItemParamsList params={params.PROPS}/>
                </div>
                <div className={`basket-item__price`}>
                    <p className={`basket-item__price-value`}>
                        <span dangerouslySetInnerHTML={{__html: `${params.PRICE_FMT}`}}/>
                        {/*<span className={`basket-item__price-unit`}>{params.itemPriceUnit}</span>*/}
                    </p>
                    <p className={`basket-item__price-description`}>{title.REACT_BASKET_FIELD_PRICE_BY} {params.RATIO} {params.MEASURE_NAME}</p>
                </div>
                <div className={`basket-item__quantity`}>
                    <QuantityCount itemCount={params.QUANTITY}
                                   maxCount={params.MAX_QUANTITY}
                                   ratio={params.RATIO}
                                   onBtnMore={onBtnMore}
                                   onBtnLess={onBtnLess}
                                   onChangeCount={onChangeCount}
                                   onBlurCount={onBlurCount}
                                   PRODUCT_ID={params.PRODUCT_ID}/>
                    <p className={`basket-item__quantity-unit`}>{params.MEASURE_NAME}</p>
                </div>
                <div className={`basket-item__total-price item-total-price`}>
                    <p className={`item-total-price__buy`}>
                        <span className={`item-total-price__buy-value`} dangerouslySetInnerHTML={{__html: params.SUM}}/>
                        {/*<span className={`item-total-price__buy-unit`}>{params.itemPriceUnit}</span>*/}
                    </p>

                    {params.OLD_PRICE_FORMAT ? (
                        <p className={`item-total-price__old-price`}>
                            <span className={`tem-total-price__old-price-value`} dangerouslySetInnerHTML={{__html: params.OLD_PRICE_FORMAT}}/>
                        </p>
                    ) : null}

                    {params.ECONOM_SUM_FORMAT ? (
                        <p className={`item-total-price__discount`}>
                            <span className={`item-total-price__discount-value`} dangerouslySetInnerHTML={{__html: params.ECONOM_SUM_FORMAT}}/>
                            <span className={`item-total-price__discount-unit`}/>
                        </p>
                    ) : null}

                </div>
                <div className={`basket-item__buttons-action`}>
                    <button className={`basket-item__buttons-favorite ${whatToShow !== 'favorite' ? '' : ' hide'}`}
                           onClick={() => {onInFavorite(params.PRODUCT_ID)}} >
                        <svg className="basket-item__buttons-favorite-icon" width="18" height="16">
                            <use xlinkHref="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_favourite_medium"></use>
                        </svg>
                    </button>
                    <button className={`basket-item__buttons-basket ${whatToShow !== 'basket' ? '' : ' hide'}`}
                            onClick={() => {onInBasket(params.PRODUCT_ID)}} >
                        <svg className="basket-item__buttons-cart-icon" width="18" height="16">
                            <use xlinkHref="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_cart"></use>
                        </svg>
                    </button>
                    <button className={`basket-item__buttons-remove`}
                            onClick={() => {onDeleteItem(params.PRODUCT_ID)}}>
                        <svg className="basket-item__buttons-favorite-icon" width="18" height="16">
                            <use xlinkHref="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_cancel_medium"></use>
                        </svg>
                    </button>
                </div>
            </li>
        );
    }
}
