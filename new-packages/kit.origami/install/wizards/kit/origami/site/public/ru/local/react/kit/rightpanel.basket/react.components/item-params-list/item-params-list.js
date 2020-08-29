import React from 'react';
import './item-params-list.scss';

const ItemParamsList = (props) => {
    const {params = null} = props;
    let listParams = [];
    if (params) {
        listParams = params.map((item) => {
            return (
                <p className={`basket-item__description-params description-params`} key={item.ID}>
                    <span className={`description-params__name`}>{item.NAME}</span>
                    <span className={`description-params__value`}>{item.VALUE}</span>
                </p>
            );
        });
    }

    return (
        <React.Fragment>
            {listParams}
        </React.Fragment>
    );

};

export default ItemParamsList;
