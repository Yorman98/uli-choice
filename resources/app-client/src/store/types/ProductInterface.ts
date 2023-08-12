export interface ProductInterface {
    id: number;
    name: string;
    slug: string;
    code: string;
    description?: string;
    price: number;
    stock: number;
    img: string;
    status: string;
    variation: string;
    unit_cost: number;
    unit_price: number;
}