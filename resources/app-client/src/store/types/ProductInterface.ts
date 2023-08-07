export interface ProductInterface {
    name: string;
    slug: string;
    code: string;
    description?: string;
    price: number;
    stock: number;
    image: string;
    status: string;
    variation: string;
    unit_cost: number;
    unit_price: number;
}